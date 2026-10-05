<?php

include("auth.php");
include("conexion.php");



/*=========================================
= VALIDAR ID
=========================================*/

if(!isset($_GET['id']) || empty($_GET['id'])){


    $_SESSION['mensaje'] = [

        "tipo" => "error",
        "texto" => "ID de la Cuenta inválido."

    ];


    header("Location: cuentas.php");
    exit();

}



/*=========================================
= OBTENER ID
=========================================*/

$id_cuenta = (int)$_GET['id'];





/*=========================================
= VERIFICAR SI EXISTE LA CUENTA
=========================================*/


$sqlVerificar = "

SELECT *

FROM cuenta

WHERE id_cuenta = ?

";



$stmt = $conn->prepare($sqlVerificar);



$stmt->execute([

    $id_cuenta

]);



$cuenta = $stmt->fetch(PDO::FETCH_ASSOC);





if(!$cuenta){


    $_SESSION['mensaje'] = [

        "tipo" => "error",
        "texto" => "La Cuenta no existe."

    ];


    header("Location: cuentas.php");
    exit();

}





/*=========================================
= ELIMINAR CUENTA
=========================================*/


$sqlEliminar = "

DELETE FROM cuenta

WHERE id_cuenta = ?

";



$stmt = $conn->prepare($sqlEliminar);





/*=========================================
= RESULTADO
=========================================*/


if(
    $stmt->execute([

        $id_cuenta

    ])
){


    $_SESSION['mensaje'] = [

        "tipo" => "success",
        "texto" => "☑️ Cuenta '".$cuenta['correo']."' eliminada correctamente."

    ];



}else{


    $_SESSION['mensaje'] = [

        "tipo" => "error",
        "texto" => "Error al eliminar la Cuenta ❌"

    ];


}





header("Location: cuentas.php");
exit();


?>