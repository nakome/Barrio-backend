<?php 

defined('BARRIO_ACCESS') or die('Sin acceso al archivo.');






// Edit
$R->Route(
    '/extension/media/(:any)/editar/(:any)',
    function ($name = '',$file = '') {
        $file = base64_decode($file);
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/media/controllers/media.class.php';
        Admin::exists();
        include EXTENSIONS.'/media/templates/edit.html';
        exit;
    }
);

// Newfile
$R->Route(
    '/extension/media/new/file',
    function ($name = '',$file = '') {
        $file = base64_decode($file);
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/media/controllers/media.class.php';
        Admin::exists();
        include EXTENSIONS.'/media/templates/newfile.html';
        exit;
    }
);

// Newfile
$R->Route(
    '/extension/media/new/folder',
    function ($name = '',$file = '') {
        $file = base64_decode($file);
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/media/controllers/media.class.php';
        Admin::exists();
        include EXTENSIONS.'/media/templates/newfolder.html';
        exit;
    }
);

// view
$R->Route(
    array(
        '/extension/media/(:any)',
        '/extension/media/(:any)/(:num)'
    ),
    function ($name = '',$num = 0) {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/media/controllers/media.class.php';
        Admin::exists();
        include EXTENSIONS.'/media/templates/index.html';
        exit;
    }
);




// Root
$R->Route(
    '/extension/media',
    function () {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/media/controllers/media.class.php';
        Admin::exists();
        $name = 'images';
        $num = 0;
        include EXTENSIONS.'/media/templates/index.html';
        exit;
    }
);
