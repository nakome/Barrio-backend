<?php

defined('BARRIO_ACCESS') or die('Sin acceso al archivo.');


// Info antes de borrar
$R->Route('/extension/plantillas/editar/(:any)', function ($name = '') {
    require_once CONTROLLERS.'/admin.class.php';
    require_once EXTENSIONS.'/plantillas/controllers/plantillas.class.php';
    $name = base64_decode($name);
    Admin::exists();
    include EXTENSIONS.'/plantillas/templates/edit.html';
    exit;
});

// Info antes de borrar
$R->Route('/extension/plantillas', function () {
    require_once CONTROLLERS.'/admin.class.php';
    require_once EXTENSIONS.'/plantillas/controllers/plantillas.class.php';
    Admin::exists();
    include EXTENSIONS.'/plantillas/templates/index.html';
    exit;
});
