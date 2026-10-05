<?php

include("auth.php");
include("conexion.php");



/*=========================================
= VALIDAR ID
=========================================*/

if (!isset($_GET['id']) || empty($_GET['id'])) {

    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "ID del servicio inválido."
    ];

    header("Location: servicios.php");
    exit();

}



/*=========================================
= OBTENER ID
=========================================*/

$idEliminar = (int)$_GET['id'];



/*=========================================
= VERIFICAR SI EXISTE EL SERVICIO
=========================================*/


$sqlVerificar = "
    SELECT
        id_servicio,
        nombre,
        descripcion,
        imagen
    FROM servicio
    WHERE id_servicio = ?
";


$stmt = $conn->prepare($sqlVerificar);

$stmt->execute([
    $idEliminar
]);


$servicio = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$servicio) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "El servicio no existe."
    ];


    header("Location: servicios.php");
    exit();

}



/*=========================================
= ELIMINAR IMAGEN FÍSICA
=========================================*/


if (
    !empty($servicio['imagen']) &&
    str_starts_with(
        $servicio['imagen'],
        'img_servicios/'
    ) &&
    file_exists($servicio['imagen'])
) {

    unlink($servicio['imagen']);

}



/*=========================================
= ELIMINAR SERVICIO
=========================================*/


$sqlEliminar = "
    DELETE
    FROM servicio
    WHERE id_servicio = ?
";


$stmt = $conn->prepare($sqlEliminar);


$resultado = $stmt->execute([
    $idEliminar
]);



/*=========================================
= RESULTADO
=========================================*/


if ($resultado) {


    $_SESSION['mensaje'] = [
        "tipo"  => "success",
        "texto" => "☑️ Servicio '" .
                    $servicio['nombre'] .
                    "' eliminado correctamente."
    ];



} else {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "Error al eliminar el servicio ❌"
    ];


}



$stmt = null;
$conn = null;



header("Location: servicios.php");
exit();

?>