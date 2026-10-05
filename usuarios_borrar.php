<?php

include("auth.php");
include("conexion.php");



/*=========================================
= VALIDAR ID
=========================================*/

if (!isset($_GET['id']) || empty($_GET['id'])) {


    $_SESSION['mensaje'] = [

        "tipo"  => "error",

        "texto" => "ID del usuario inválido."

    ];


    header("Location: usuarios.php");

    exit();

}




/*=========================================
= OBTENER IDS
=========================================*/

$idEliminar = (int)$_GET['id'];

$idSesion   = (int)$_SESSION['id_usuario'];





/*=========================================
= EVITAR ELIMINAR SESIÓN ACTUAL
=========================================*/

if($idEliminar === $idSesion){


    $_SESSION['mensaje'] = [

        "tipo" => "error",

        "texto" => "No puedes eliminar tu propia cuenta mientras tienes la sesión iniciada. ❌"

    ];


    header("Location: usuarios.php");

    exit();

}






/*=========================================
= VERIFICAR SI EXISTE EL USUARIO SQLITE
=========================================*/


$sqlVerificar = "

SELECT

    id_usuario,

    nombre,

    imagen

FROM usuario

WHERE id_usuario = ?

";



$stmt = $conn->prepare($sqlVerificar);



$stmt->execute([

    $idEliminar

]);



$usuario = $stmt->fetch();





if(!$usuario){


    $_SESSION['mensaje'] = [

        "tipo"  => "error",

        "texto" => "El usuario no existe."

    ];


    header("Location: usuarios.php");

    exit();

}







/*=========================================
= ELIMINAR IMAGEN FÍSICA
=========================================*/


if(

    !empty($usuario['imagen'])

    &&

    str_starts_with(
        $usuario['imagen'],
        'img_usuarios/'
    )

    &&

    file_exists($usuario['imagen'])

){


    unlink($usuario['imagen']);

}







/*=========================================
= ELIMINAR USUARIO SQLITE
=========================================*/


$sqlEliminar = "

DELETE

FROM usuario

WHERE id_usuario = ?

";



$stmt = $conn->prepare($sqlEliminar);




if(

    $stmt->execute([

        $idEliminar

    ])

){



    $_SESSION['mensaje'] = [

        "tipo"  => "success",

        "texto" => "🗑️ Usuario '".$usuario['nombre']."' borrado correctamente."

    ];



}else{



    $_SESSION['mensaje'] = [

        "tipo"  => "error",

        "texto" => "Error al eliminar el usuario ❌"

    ];

}



header("Location: usuarios.php");

exit();


?>