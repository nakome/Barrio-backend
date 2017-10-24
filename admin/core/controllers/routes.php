<?php defined('BARRIO_ACCESS') or die('Sin accesso a este script.');
// iniciamos el router
$R = new Router();

// create dir if not exists
if (!is_dir(EXTENSIONS)) @mkdir(EXTENSIONS);
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
        $routesFile = EXTENSIONS.'/'.$name.'/index.php';
        if ($enabled) {
            if (file_exists($routesFile)) {
                include $routesFile;
            }
        }
    }
}

// Borrar archivo
$R->Route('/borrar/archivo/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);

    $archivo = ROOTBASE.'/content/'.$name.'.md';
    if (File::delete($archivo)) {
        if (Dir::exists(ROOTBASE.'/content/imagenes/'.$name)) {
            Dir::delete(ROOTBASE.'/content/imagenes/'.$name);
        }
        Message::set('Bien !', 'El archivo ha sido borrado');
        Url::redirect(Url::base());
    } else {
        Message::set('Error !', 'El archivo no ha sido borrado');
        Url::redirect(Url::base());
    }
});
// Borrar carpeta
$R->Route('/borrar/directorio/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    $archivo = ROOTBASE.'/content/'.$name;
    if (Dir::delete($archivo)) {
        if (!Dir::exists(ROOTBASE.'/content/imagenes/'.$archivo)) {
            Dir::delete(ROOTBASE.'/content/imagenes/'.$archivo);
        }
        Message::set('Bien !', 'El directorio ha sido borrado');
        Url::redirect(Url::base());
    } else {
        Message::set('Error !', 'El directorio no ha sido borrado');
        Url::redirect(Url::base());
    }
});
// Info antes de borrar
$R->Route('/info/borrar/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    include VIEWS.'/borrar-directorio.html';
    exit;
});


// subir imagen
$R->Route('/subir/imagen/(:any)', function ($name = '') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    include VIEWS.'/subir-imagenes.html';
    exit;
});
// preview imagen
$R->Route('/ver/imagen/(:any)', function ($name = '') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    include VIEWS.'/ver-imagenes.html';
    exit;
});



// Crear pagina
$R->Route(array('/crear/pagina/(:any)','/crear/pagina'), function ($name = '') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    include VIEWS.'/crear-pagina.html';
    exit;
});
// Crear directorio
$R->Route('/crear/directorio', function () {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = '';
    include VIEWS.'/crear-directorio.html';
    exit;
});


// Cambiar url
$R->Route('/cambiar/url/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    $name = base64_decode($name);
    include VIEWS.'/cambio-de-nombre.html';
    exit;
});


// Editar directorio
$R->Route('/editar/directorio/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    $name = base64_decode($name);
    Admin::exists();
    include VIEWS.'/editar-directorio.html';
    exit;
});
// Editar archivo
$R->Route('/editar/archivo/(:any)', function ($name='') {
    require_once CONTROLLERS.'/admin.class.php';
    $name = base64_decode($name);
    Admin::exists();
    include VIEWS.'/editar-archivo.html';
});


// Salir
$R->Route('/borrar/log', function () {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    Admin::cleanLog();
});


// Salir
$R->Route('/usuario/salir', function () {
    require_once CONTROLLERS.'/admin.class.php';
    Admin::exists();
    Admin::logout();
});

// Principal
$R->Route('/', function () {
    require_once CONTROLLERS.'/admin.class.php';
    if (Session::get('Barrio_uid') && Session::get('Barrio_token')) {
        include VIEWS.'/dashboard.html';
    } else {
        Admin::auth();
        include VIEWS.'/login.html';
        exit;
    }
});
// Iniciar router
$R->launch();
