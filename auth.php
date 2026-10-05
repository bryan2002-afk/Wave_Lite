<?php

session_start();

/*=========================================
= EVITAR CACHE
=========================================*/
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


/*=========================================
= VALIDAR SI HAY SESIÓN
=========================================*/
if (!isset($_SESSION['id_usuario'])) {

    header("Location: login.php");
    exit();

}


/*=========================================
= FUNCIÓN DE PERMISOS (para futuro)
=========================================*/
function tiene_permiso($permiso = null){

    // Aquí puedes agregar lógica de permisos después
    return true;

}


/*=========================================
= DATOS DEL USUARIO LOGUEADO
=========================================*/

$id_usuario = $_SESSION['id_usuario'] ?? null;

$nombre = $_SESSION['nombre'] ?? '';

$name_1 = $_SESSION['name'] ?? '';

$imagen = $_SESSION['imagen'] ?? 'img_usuarios/perfil.png';


?>