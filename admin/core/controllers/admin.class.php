<?php  defined('BARRIO_ACCESS') or die('Sin accesso a este script.');


/**
 *  Admin class functions
 *
 * @author Moncho Varela <nakome@gmail.com>
 *
 * @version 1.0
 *
 */
class Admin
{

    /**
     * Load extensions
     *
     * @return array
     */
    public static function loadExtensionsList()
    {
        // http://stackoverflow.com/questions/14680121/include-just-files-in-scandir-array
        $extensions = array_filter(scandir(EXTENSIONS), function ($item) {
            return $item[0] !== '.';
        });

        foreach ($extensions as $ext) {
            $jsonFile = EXTENSIONS.'/'.$ext.'/config.json';
            // if exists json file load plugin
            if (file_exists($jsonFile)) {
                // get contents of file
                $extensionsFile = file_get_contents($jsonFile);
            }
            // convert to json
            $json = json_decode($extensionsFile, true);
            // loop to read json
            if (!is_array($json)) {
                continue;
            }

            foreach ($json as $obj) {
                $name = $obj['filename'];
                $enabled = $obj['enabled'];
                if ($enabled) {
                    echo '<li class="p-2">
                        <a href="'.Url::base().'/extension/'.$obj['filename'].'" class="text-deco-none" >
                            '.$obj['name'].' <i class="text-info">ext</i>
                        </a>
                    </li>';
                }
            }
        }
    }

    /**
     * Record logs
     *
     * @param string $desc the data
     *
     */
    public static function log($desc = '')
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            $data = json_decode(File::getContent($file), true);
            $data[] = array(
                'date' => date('d/m/Y H:i:s'),
                'desc' => $desc
            );
            File::setContent($file, json_encode($data));
        }
    }

    /**
     * Get Log
     *
     * @return array
     */
    public static function getLog()
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            $data = json_decode(File::getContent($file), true);
            if (is_array($data)) {
                return $data;
            }
        }
    }

    /**
     * Clean Log
     *
     * @return  boolean
     */
    public static function cleanLog()
    {
        $file = ROOT.'/logs.txt';
        if (File::exists($file)) {
            File::setContent($file, '[]');
            Message::set(L::success, L::ilog_deletefile);
            Url::redirect(Url::base());
        }
    }

    /**
     * Check login
     *
     * @return boolean
     */
    public static function exists()
    {
        if (Session::get('Barrio_uid') &&
            Session::get('Barrio_token') &&
            Session::get('Barrio_time')) {
            return true;
        } else {
            self::log(L::ilog_attempsaccess);
            Url::redirect(Url::base());
        }
    }


    /**
     * Login
     *
     * @return string
     */
    public static function auth()
    {
        // init attemps
        $attemps = Session::get('Barrio_attemps');
        if ($attemps > 4) {
            setcookie('blockUser', true, time() + (60 * 5)); //(60 * 20) for 20 minutes
            Session::set('Barrio_attemps', 0);
            die('<style>body{text-align:center}</style><h2>'.L::error.', '.L::login_manyattemps.'</h2>');
            self::log(L::ilog_attemps.' '.$attemps);
            exit();
        }

        if (isset($_COOKIE['blockUser'])) {
            die('<style>body{text-align:center}</style><h2>Error, '.L::ilog_manyattemps.'</h2>');
            exit();
        } else {
            if (Url::post('auth')) {
                if (Token::check(Url::post('token'))) {
                    $password = sha1(md5(trim(Url::post('password'))));
                    if ($password == Router::$config['password']) {
                        // session set
                        Session::set('Barrio_uid', md5(uniqid()));
                        Session::set('Barrio_token', Token::generate());
                        Session::set('Barrio_time', time());
                        // set login attemps to 0 and redirect
                        Session::set('Barrio_attemps', 0);
                        self::log(L::ilog_userlogin);
                        Url::redirect(Url::base());
                    } else {
                        $count = $attemps + 1;
                        Session::set('Barrio_attemps', $count);
                        self::log(L::ilog_attemps.' '.$count);
                        Message::set(
                            L::error,
                            L::ilog_badpassword.''.L::failures.': '.Session::get('Barrio_attemps'));
                        Url::redirect(Url::base());
                    }
                } else {
                    die('CRSF detect !');
                }
            }
        }
    }

    /**
     * Logout
     *
     * @return boolean
     */
    public static function logout()
    {
        Session::delete('Barrio_uid');
        Session::delete('Barrio_token');
        Session::delete('Barrio_time');
        Session::destroy();
        self::log(L::ilog_userlogout);
        Url::redirect(Url::base());
        exit;
    }

    /**
     * Get Page headers
     *
     * @param string $name nombre de la pagina
     *
     * @return array;
     */
    public static function getPageHeaders($name)
    {
        $archivo = ROOTBASE.'/content/'.$name.'.md';
        $contenido = file_get_contents($archivo);
        $pagina = explode('----', $contenido);

        $headers = array(
            'title' => 'Title',
            'description' => 'Description',
            'tags' => 'Tags',
            'author' => 'Author',
            'image' => 'Image',
            'date' => 'Date',
            'robots' => 'Robots',
            'keywords' => 'Keywords',
            'template' => 'Template',
            'url' => 'Url',
            'category' => 'Category',
            'published' => 'Published'
        );

        foreach ($headers as $campo => $regex) {
            if (preg_match('/^[ \\t\\/*#@]*'.preg_quote($regex, '/').':(.*)$/mi', $pagina[0], $match) && $match[1]) {
                $var[$campo] = trim($match[1]);
            } else {
                $var[$campo] = '';
            }
        }
        return $var;
    }


    /**
     * Get Page content
     *
     * @param string $name nombre de la pagina
     *
     * @return array;
     */
    public static function getPageContent($name = '')
    {
        $archivo = ROOTBASE.'/content/'.$name.'.md';
        $contenido = file_get_contents($archivo);
        $pagina = explode('----', $contenido);
        return $pagina[1];
    }


    /**
     * Save md file
     *
     * @param string $dir Folder
     * @param string $name name of page
     * @param string $location location folder
     *
     * @return boolean
     */
    public static function saveFile($dir = '', $name = '', $location='directorio')
    {
        if (Url::post('guardar')) {
            if (Token::check(Url::post('token'))) {
                $title = (Url::post('title')) ? Url::post('title') : '';
                $keywords = (Url::post('keywords')) ? Url::post('keywords') : '';
                $template = (Url::post('template')) ? Url::post('template') : 'index';
                $published = (Url::post('published') == 'si') ? 'true' : 'false';
                $author = (Url::post('author')) ? Url::post('author') : '';
                $date = (Url::post('date')) ? Url::post('date') : date('d/m/Y');
                $robots = (Url::post('robots')) ? Url::post('robots') : 'index,follow';
                $image = (Url::post('image')) ? Url::post('image') : '';
                $description = (Url::post('description')) ? Url::post('description') : '';
                $content = (Url::post('content')) ? Url::post('content') : '';

                $html = "Title: $title\n";
                $html .= "Description: $description\n";
                $html .= "Keywords: $keywords\n";
                $html .= "Published: $published\n";
                $html .= "Author: $author\n";
                $html .= "Date: $date\n";
                $html .= "Image: $image\n";
                $html .= "Robots: $robots\n";
                $html .= "Template: $template\n";
                $html .= "----\n";
                $html .= "$content";

                if (File::setContent($dir, $html)) {
                    Message::set(L::success, L::ilog_savefile);
                    self::log(L::ilog_updatefile.' '.$name);
                    Url::redirect(Url::base().'/editar/'.$location.'/'.base64_encode($name));
                } else {
                    Message::set(L::error, L::ilog_errsavefile);
                    Url::redirect(Url::base().'/editar/'.$location.'/'.base64_encode($name));
                };
            } else {
                die('CRF detect!');
            }
        }
    }


    /**
     * Get Page Info
     *
     * @param string $folder the name of folder
     * @param string $item  the name of page
     *
     * @return array
     */
    public static function getPageInfo($folder = '', $item = '')
    {

        // convierte en direccion para vista previa
        $url = str_replace(ROOTBASE.'/content', Url::base(), $item);
        $url = str_replace('.md', '', $url);
        $url = str_replace('admin/', '', $url);
        $url = str_replace('\\', '/', $url);

        $output[] = array(
            // nombre
            'name' => basename($item, '.md'),
            // extension
            'extension' => pathinfo($item, PATHINFO_EXTENSION),
            // url vista previea
            'url' => $url,
            // direccion del archivo de la carpeta content
            'src' => str_replace($folder, '', $item),
            // última modificación de un archivo
            'last_update' => date('d/m/Y H:i', filemtime($item)),
            // Obtiene el momento del último cambio del i-nodo de un archivo
            'last_change' => date('d/m/Y H:i', filectime($item)),
            // Obtiene el momento del último acceso a un archivo
            'last_access' => date('d/m/Y H:i', fileatime($item)),
            // tamaño del archivo en bytes
            'filesize' => filesize($item).' bytes',
            // Se puede leer
            'is_readable' => is_readable($item),
            // Se puede Escribir
            'is_writable' => is_writable($item),
            // Es ejecutable
            'is_executable' => is_executable($item),
            // Permisos del archivo
            'fileperms' => substr(sprintf('%o', fileperms($item)), -4)
        );

        return $output;
    }
}
