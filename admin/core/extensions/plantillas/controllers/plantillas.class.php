<?php

defined('BARRIO_ACCESS') or die('No direct script access.');

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Plantillas
{

    /**
     * Language function
     *
     * @param string $name the name
     */
    public static $lang;
    public static function lang($name = 'es')
    {
        $file = EXTENSIONS.'/plantillas/lang.php';
        if (file_exists($file) && is_file($file)) {
             static::$lang = (include $file);
             return static::$lang[$name];
        } else {
            die('Oops.. Donde esta el archivo de configuración ?!');
        }
    }


    /**
     * Load Config
     *
     * @param string $name name of config var
     *
     * @return array
     */
    public static function config($name = '')
    {
        $config = include ROOTBASE.'/config.php';
        return $config[$name];
    }

    /**
     * Load files
     *
     * @param string $type type of file
     *
     * @return array
     */
    public static function load($type = 'html')
    {
        $config = include ROOTBASE.'/config.php';
        $files = File::scan(ROOTBASE.'/themes/'.$config['theme'], array($type));
        $arr = array();
        foreach ($files as $file) {
            $arr[] = $file;
        }
        return $arr;
    }

    /**
     * Load Readme
     *
     * @return string
     */
    public static function loadReadme()
    {
        $config = include ROOTBASE.'/config.php';
        include ROOTBASE.'/extensions/markdown/Parsedown/Parsedown.php';
        include ROOTBASE.'/extensions/markdown/Parsedown/ParsedownExtra.php';
        $readme = ROOTBASE.'/themes/'.$config['theme'].'/README.md';
        if (File::exists($readme)) {
            return Parsedown::instance()->text(File::getContent($readme));
        } else {
            return '<div class="alert alert-info">'.$lang['readmeinfo'].'</div>';
        }
    }

    /**
     * Save file
     *
     * @param string $file the file
     *
     * @return func
     */
    public static function saveFile($file = '')
    {
        if (Url::post('guardar')) {
            if (Token::check(Url::post('token'))) {
                if (File::exists($file)) {
                    try {
                        chmod($file, 0755);
                        File::setContent($file, Url::post('content'));
                        chmod($file, 0644);
                        Message::set(L::success, L::ilog_updatefile);
                        Admin::log('Editado archivo '.File::name($file).' en Plantillas');
                        Url::redirect(Url::base().'/extension/plantillas/editar/'.base64_encode($file));
                    } catch (Exception $e) {
                        Message::set(L::error, L::ilog_errupdatefile);
                        Admin::log(L::error.' '.File::name($file).' '.$e);
                        Url::redirect(Url::base().'/extension/plantillas/editar/'.base64_encode($file));
                    }
                }
            }
        }

        if (Url::post('guardarysalir')) {
            if (Token::check(Url::post('token'))) {
                if (File::exists($file)) {
                    try {
                        chmod($file, 0755);
                        File::setContent($file, Url::post('content'));
                        chmod($file, 0644);
                        Message::set(L::success, L::ilog_updatefile);
                        Admin::log('Editado archivo '.File::name($file).' en Plantillas');
                        Url::redirect(Url::base().'/extension/plantillas');
                    } catch (Exception $e) {
                        Message::set(L::success, L::ilog_errupdatefile);
                        Admin::log(L::error.' '.File::name($file).' '.$e);
                        Url::redirect(Url::base().'/extension/plantillas');
                    }


                }
            }
        }
    }
}
