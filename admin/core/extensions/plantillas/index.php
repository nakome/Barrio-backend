<?php

defined('BARRIO_ACCESS') or die('Sin acceso al archivo.');


// Editar
$R->Route(
    '/extension/plantillas/editar/(:any)',
    function ($name = '') {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/plantillas/controllers/plantillas.class.php';
        $name = base64_decode($name);
        Admin::exists();
        $lang = Plantillas::lang(Router::$config['lang']);
        include EXTENSIONS.'/plantillas/templates/edit.html';
        exit;
    }
);

// Root
$R->Route(
    '/extension/plantillas',
    function () {
        include CONTROLLERS.'/admin.class.php';
        include EXTENSIONS.'/plantillas/controllers/plantillas.class.php';
        Admin::exists();
        $lang = Plantillas::lang(Router::$config['lang']);
        include EXTENSIONS.'/plantillas/templates/index.html';
        exit;
    }
);
