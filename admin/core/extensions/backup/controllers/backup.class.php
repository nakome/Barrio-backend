<?php

defined('BARRIO_ACCESS') or die('No direct script access.');




/**
 * @author      Moncho Varela / Nakome <nakome@gmail.com>
 * @copyright   2016 Moncho Varela / Nakome <nakome@gmail.com>
 *
 * @version     0.0.1
 */
class Backup
{

    /**
     * Language function
     *
     * @param string $name the name
     */
    public static $lang;
    public static function lang($name = 'es')
    {
        $file = EXTENSIONS.'/backup/lang.php';
        if (file_exists($file) && is_file($file)) {
             static::$lang = (include $file);
             return static::$lang[$name];
        } else {
            die('Oops.. Donde esta el archivo de configuración ?!');
        }
    }



    /**
     * Get all backups
     *
     * @return array the array
     */
    public static function getAll()
    {
        $backupDir = ROOTBASE.'/backups';
        if (Dir::exists($backupDir)) {
            $files = File::scan($backupDir);
            return $files;
        }
    }

    /**
     * Generate backup
     *
     * @return string
     */
    public static function generate()
    {
        $files = ROOTBASE.'/content/';
        $backupDir = ROOTBASE.'/backups/';
        if (!Dir::exists($backupDir)) {
            Dir::create($backupDir);
        }
        $zip = self::zip($files, $backupDir.'/backup-'.date('d-m-Y').'.zip');
        if ($zip) {
            Admin::log('Backup creado');
            Message::set('Bien!', 'El backup ha sido creado');
            Url::redirect(Url::base().'/extension/backup');
        }else{
            Admin::log('Error al crear un Backup');
            Message::set('Error!', 'Lo siento el archivo no se ha podido crear');
            Url::redirect(Url::base().'/extension/backup');
        }
    }

    /**
     * Delete backup
     *
     * @param string $file the file
     *
     * @return boolean
     */
    public static function delete($file = '')
    {
        $zip = File::delete(base64_decode($file));
        if ($zip) {
            Admin::log('Backup borrado');
            Message::set('Bien!', 'El backup ha sido Borrado');
            Url::redirect(Url::base().'/extension/backup');
        }else{
            Admin::log('Backup se intento borrar');
            Message::set('Error!', 'Lo siento el archivo no se ha podido borrar');
            Url::redirect(Url::base().'/extension/backup');
        }
    }

    public static function download($file)
    {
        $file = base64_decode($file);
        $filename = File::name($file).'.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file)); //Absolute URL
        ob_clean();
        flush();
        readfile($file); //Absolute URL
        exit();
    }
    /**
     * Zip files
     *
     * @param string $source      the folder to zip
     * @param string $destination the ouput file
     *
     * @return callback
     */
    public static function zip($source = '', $destination = '')
    {
        if (extension_loaded('zip') === true) {
            if (file_exists($source) === true) {
                $zip = new ZipArchive();

                if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
                    $source = realpath($source);

                    if (is_dir($source) === true) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                        foreach ($files as $file) {
                            $file = realpath($file);

                            if (is_dir($file) === true) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else if (is_file($file) === true) {
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source) === true) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }

    /**
     * Unzip archives
     *
     * @param string $dir the dir
     *
     * @return boolean
     */
    public static function unZip($dir)
    {
        /**
         * Remove all directories
         */
        function rmdirRecursive($dir)
        {
            foreach (scandir($dir) as $file) {
                if ('.' === $file || '..' === $file) continue;
                if (is_dir("$dir/$file")) rmdirRecursive("$dir/$file");
                else unlink("$dir/$file");
            }
            rmdir($dir);
        }

        if ($_FILES["zip_file"]["name"]) {
            $filename = $_FILES["zip_file"]["name"];
            $source = $_FILES["zip_file"]["tmp_name"];
            $type = $_FILES["zip_file"]["type"];

            $name = explode(".", $filename);
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
            foreach ($accepted_types as $mime_type) {
                if ($mime_type == $type) {
                    $okay = true;
                    break;
                }
            }

            $continue = strtolower($name[1]) == 'zip' ? true : false;
            if (!$continue) {
                Message::set('El archivo que esta tratando de subir no es en formato zip');
                Url::redirect(Url::base().'/extension/backup');
            }

            /* PHP current path */
            $path = $dir;  // absolute path to the directory where zipper.php is in
            $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
            $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)

            $targetdir = $path; // target directory
            $targetzip = $path . $filename; // target zip file

            /* create directory if not exists', otherwise overwrite */
            /* target directory is same as filename without extension */

            if (is_dir($targetdir)) rmdirRecursive ( $targetdir);

            Dir::create($targetdir);

            /* here it is really happening */
            if (move_uploaded_file($source, $targetzip)) {
                $zip = new ZipArchive();
                $x = $zip->open($targetzip);  // open the zip file to extract
                if ($x === true) {
                    $zip->extractTo($targetdir); // place in the directory with same name
                    $zip->close();
                    File::delete($targetzip);
                }

                if (Dir::exists(ROOTBASE.'/content/var')) {
                    Dir::delete(ROOTBASE.'/content/var');
                }
                Admin::log('Backup descromprimido');
                Message::set('El archivo ha sido descomprimido');
                Url::redirect(Url::base().'/extension/backup');
            } else {
                Admin::log('Backup no se ha podido descomprimir');
                essage::set('El archivo no podido ser descomprimido, por favor vuelva a intentarlo');
                Url::redirect(Url::base().'/extension/backup');
            }
        }

    }
}
