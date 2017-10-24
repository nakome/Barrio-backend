<?php

defined('BARRIO_ACCESS') or die('Sin acceso al archivo.');


// Info antes de borrar
$R->Route('/extension/demo', function () {
    require_once CONTROLLERS.'/admin.class.php';
    require_once EXTENSIONS.'/demo/controllers/demo.class.php';
    Admin::exists();
    include EXTENSIONS.'/demo/templates/index.html';
    exit;
});
