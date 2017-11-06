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
            return '<div class="alert alert-info">Esta plantilla no incluye README.md</div>';
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
                    chmod($file, 0755);
                    File::setContent($file, Url::post('content'));
                    chmod($file, 0644);
                    Message::set('Bien !', 'El archivo ha sido actualizado');
                    Admin::log('Editado archivo '.File::name($file).' en Plantillas');
                    Url::redirect(Url::base().'/extension/plantillas/editar/'.base64_encode($file));
                }
            }
        }

        if (Url::post('guardarysalir')) {
            if (Token::check(Url::post('token'))) {
                if (File::exists($file)) {
                    File::setContent($file, Url::post('content'));
                    Message::set('Bien !', 'El archivo ha sido actualizado');
                    Admin::log('Editado archivo '.File::name($file).' en Plantillas');
                    Url::redirect(Url::base().'/extension/plantillas');
                }
            }
        }
    }
}
