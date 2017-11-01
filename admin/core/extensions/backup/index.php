<?php 

defined('BARRIO_ACCESS') or die('Sin acceso al archivo.');


// Download
$R->Route(
    '/extension/backup/download/(:any)/(:any)',
    function ($token = '', $name = '') {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/backup/controllers/backup.class.php';
        Admin::exists();
        if (Token::check($token)) {
            Backup::download($name);
        } else {
            die('CRSF detect!');
        }
        exit;
    }
);

// Delete
$R->Route(
    '/extension/backup/delete/(:any)/(:any)',
    function ($token = '', $name = '') {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/backup/controllers/backup.class.php';
        Admin::exists();
        if (Token::check($token)) {
            Backup::delete($name);
        } else {
            die('CRSF detect!');
        }
        exit;
    }
);


// Create
$R->Route(
    '/extension/backup/import',
    function () {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/backup/controllers/backup.class.php';
        Admin::exists();

        if (array_key_exists('import', $_POST)) {
            if (Token::check($_POST['token'])) {
                if (array_key_exists('zip_file', $_FILES)) {
                   Backup::unzip(ROOTBASE.'/content/');
                }
            } else {
                die(' CRSF detectado');
            }
        }


        include EXTENSIONS.'/backup/templates/import.html';
        exit;
    }
);


// Create
$R->Route(
    '/extension/backup/create',
    function () {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/backup/controllers/backup.class.php';
        Admin::exists();
        Backup::generate();
        exit;
    }
);

// Root
$R->Route(
    '/extension/backup',
    function () {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/backup/controllers/backup.class.php';
        Admin::exists();
        include EXTENSIONS.'/backup/templates/index.html';
        exit;
    }
);
