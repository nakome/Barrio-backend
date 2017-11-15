<?php

defined('BARRIO_ACCESS') or die('No direct script access.');

/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Media
{

    /**
     * Format bytes
     *
     * @param number $bytes      the bytes
     * @param number $precission the precission
     *
     * @return number
     */
    public static function format($bytes, $precision = 2) {
        $base = log($bytes, 1024);
        $suffixes = array('', 'Kb', 'Mb', 'Gb', 'Tb');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    /**
     * Upload files
     *
     * @return string
     */
    public static function upload()
    {
        if (Url::post('uploadFile')) {
            if (Url::post('token')) {
                $files = $_FILES['file']['name'];
                // create if not exists
                $dir =  ROOTBASE.'/content/imagenes/media/';

                // max storage file
                $file_size = $_FILES['file']['size'];
                $maxFileUpload = ini_get('upload_max_filesize');
                $mfz = $maxFileUpload*1000000;
                if ($file_size > $mfz) {
                    Message::set('Error!','Archivo muy largo maximo '.$maxFileUpload);
                    Url::redirect(Url::base().'/extension/media/new/file');
                    exit;
                }

                if (!Dir::exists($dir)) {
                    Dir::create($dir, 0777);
                }

                $fileUploaded = $dir.Url::parse(File::name($_FILES['file']['name'])).'.'.File::ext($_FILES['file']['name']);

                $filetypes = array(
                    'jpg','jpeg','png','gif','svg',
                    'webm','mp4','ogg','mov','wav',
                    'mp3','ogg','txt','doc','pdf',
                    'zip','tar','tgz','rar','flv',
                    '3gp','mkv','ogv'
                );
                if (!in_array(File::ext($_FILES['file']['name']), $filetypes)) {
                    Message::set('Error!','Este archivo no esta permitido');
                    Url::redirect(Url::base().'/extension/media/new/file');
                }

                if (File::exists($fileUploaded)) {
                    Message::set('Error!','El archivo ya existe');
                    Url::redirect(Url::base().'/extension/media/new/file');
                } else {

                    if (move_uploaded_file($_FILES['file']['tmp_name'], $fileUploaded)) {
                        Message::set('Bien!','El archivo ha sido subido');
                        Url::redirect(Url::base().'/extension/media/');
                    }
                }


            } else {
                die('crsf Detect !');
            }
        }

    }

    /**
     * Archive preview
     *
     * @param string $name the type of file
     * @param string $num  num of page
     *
     * @return string
     */
    public static function preview($name = 'images',$num = 0)
    {
        $limit = 6;

        $filetypesImages = array('jpg','jpeg','png','gif','svg');
        $filetypesVideo = array('webm','mp4','ogg','mov','flv','3gp','mkv');
        $filetypesMusic = array('wav','mp3','ogg');
        $filetypesDocs = array('txt','doc','pdf');
        $filetypesOthers = array('zip','tar','tgz','rar');

        // create if not exists
        $dir =  ROOTBASE.'/content/imagenes';
        if (!Dir::exists($dir)) {
            Dir::create($dir, 0777);
        }

        $files = '';
        switch($name){
        case 'images':
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesImages);
            break;
        case 'video':
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesVideo);
            break;
        case 'music':
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesMusic);
            break;
        case 'documents':
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesDocs);
            break;
        case 'others':
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesOthers);
            break;
        default:
            $files = File::scan(ROOTBASE.'/content/imagenes/', $filetypesImages);
            break;
        }


        if ($files) {

            //intialize a new array of files that we want to show
            $arr = array();
            //add a file to the $goodfiles array if its name doesn't begin with a period
            foreach ($files as $f) {
                // Insert one or more elements in array
                array_push($arr, $f);
            }
            // Divide an array into fragments
            $arrayOfFiles = array_chunk($arr, $limit);
            // Get numer
            $pgkey = $num;
            $items = $arrayOfFiles[$pgkey];


            $html = '';


            foreach ($items as $file) {

                $url = str_replace(ROOTBASE, Url::web(), $file);
                $url = str_replace('\\', '/', $url);
                $url = rtrim($url, '/');

                $images = '<img src="'.$url.'"/>';
                $video = '<i class="icon-video isIcon"></i> '.File::name($file).'.'.File::ext($file);
                $music = '<i class="icon-mic isIcon"></i> '.File::name($file).'.'.File::ext($file);
                $others = '<i class="icon-document isIcon"></i> '.File::name($file).'.'.File::ext($file);

                $template = '';
                switch($name){
                case 'images':
                    $template .= $images;
                    break;
                case 'video':
                    $template .= $video;
                    break;
                case 'music':
                    $template .= $music;
                    break;
                case 'documents':
                    $template .= $others;
                    break;
                case 'others':
                    $template .= $others;
                    break;
                }

                $html .= '<div class="thumb text-center">
                        '.$template.'
                        <div class="options">
                            <a class="text-dark text-deco-none mr-2"
                                href="'.$url.'">
                                <i class="icon-download mr-2"></i> Download
                            </a>
                            <a class="text-dark text-deco-none"
                                href="'.Url::base().'/extension/media/'.$name.'/editar/'.base64_encode($file).'">
                                <i class="icon-pencil mr-2"></i> Editar
                            </a>
                        </div>
                    </div>';

            }

            // total = post / limit - 1
            $total = ceil(count($files)/$limit);
            $html .= '<nav class="pagination">';
            $html .= ($num != 0) ? '<a href="'.Url::base().'/extension/media/'.$name.'/'.($num - 1).'"><i class="icon-chevron-thin-left"></i></a>' : '';
            $html .= ($num != ($total - 1)) ? '<a href="'.Url::base().'/extension/media/'.$name.'/'.($num + 1).'"><i class="icon-chevron-thin-right"></i></a>' : '';
            $html .= '</nav>';

            return $html;
        } else {
            return '<div class="alert alert-info">Nada por aqui..</div>';
        }
    }

    /**
     * Preview for preview image
     *
     * @param string $name  the name
     * @param string $file  the file
     * @param string $image the image
     */
    public static function editPreview($name= '',$file = '',$image = '')
    {
        if (Url::post('borrar')){
            if (Token::check(Url::post('token'))) {
                File::delete($file);
                if (!Dir::exists($file)) {
                    Message::set('Bien !','El archivo ha sido borrado');
                    Url::redirect(Url::base().'/extension/media/');
                    exit;
                } else {
                    Message::set('Error !','No se ha podido borrar el archivo');
                    exit;
                }
            } else {
                die('CRSF detectado!');
            }
        }

        $directorioDelArchivo = str_replace(File::name($file).'.'.File::ext($file), '', $file);
        if (Url::post('renombrar')) {
            if (Token::check(Url::post('token'))) {
                $nuevoNombre = $directorioDelArchivo.Url::parse(Url::post('name')).'.'.File::ext($file);
                File::rename($file,$nuevoNombre);
                if (File::exists($nuevoNombre)) {
                    Message::set('Bien !','El archivo ha sido renombrado');
                    Url::redirect(Url::base().'/extension/media/');
                    exit;
                } else {
                    Message::set(
                        'Error !',
                        'No se ha podido cambiar el nombre de el archivo'
                    );
                    exit;
                }
            } else {
                die('CRSF detectado!');
            }
        }

        $images = '<img src="'.$image.'"/>';
        $video  = '<i class="icon-video isIcon"></i> '.File::name($image).'.'.File::ext($image);
        $music  = '<i class="icon-mic isIcon"></i> '.File::name($image).'.'.File::ext($image);
        $others = '<i class="icon-document isIcon"></i> '.File::name($image).'.'.File::ext($image);

        $allowExt = array();
        $template = '';
        switch($name){
        case 'images':
            $template .= $images;
            $allowExt = array('jpg','jpeg','png','gif','svg');
            break;
        case 'video':
            $template .= $video;
            $allowExt = array('webm','mp4','ogg','mov','flv','3gp','mkv');
            break;
        case 'music':
            $template .= $music;
            $allowExt = array('wav','mp3','ogg');
            break;
        case 'documents':
            $template .= $others;
            $allowExt = array('txt','doc','pdf');
            break;
        case 'others':
            $template .= $others;
            $allowExt = array('zip','tar','tgz','rar');
            break;
        }

        $html = '';
        if (in_array(File::ext($file), $allowExt)) {
            $html .= '<div class="image-preview">'.$template.'</div>';
            $html .= '<pre class="bg-dark text-white p-3" style="overflow-x:auto;">![]('.$image.')</pre>';
        }
        echo $html;
    }
}
