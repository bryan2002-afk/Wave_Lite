<?php

include("auth.php");
include("conexion.php");



/*=========================================
= VALIDAR ID
=========================================*/

if (!isset($_GET['id']) || empty($_GET['id'])) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "ID de la Categoría inválido."
    ];


    header("Location: categorias.php");
    exit();

}




/*=========================================
= OBTENER ID
=========================================*/

$idEliminar = (int)$_GET['id'];




/*=========================================
= VERIFICAR SI EXISTE LA CATEGORÍA
=========================================*/

$sqlVerificar = "
    SELECT
        id_categoria,
        nombre,
        descripcion
    FROM categoria
    WHERE id_categoria = ?
";



$stmt = $conn->prepare($sqlVerificar);



if (!$stmt) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "Error al preparar la consulta."
    ];


    header("Location: categorias.php");
    exit();

}



$stmt->execute([
    $idEliminar
]);



$categoria = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$categoria) {


    $stmt->closeCursor();


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "La Categoría no existe."
    ];


    header("Location: categorias.php");
    exit();

}



$stmt->closeCursor();





/*=========================================
= ELIMINAR CATEGORÍA
=========================================*/

$sqlEliminar = "
    DELETE
    FROM categoria
    WHERE id_categoria = ?
";



$stmt = $conn->prepare($sqlEliminar);



if (!$stmt) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "Error al preparar la consulta."
    ];


    header("Location: categorias.php");
    exit();

}




/*=========================================
= RESULTADO
=========================================*/

if (
    $stmt->execute([
        $idEliminar
    ])
) {


    $_SESSION['mensaje'] = [
        "tipo"  => "success",
        "texto" => "🗑️ Categoría '" . $categoria['nombre'] . "' eliminada."
    ];



} else {



    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "❌ Error al eliminar la categoría."
    ];

}



$stmt->closeCursor();



header("Location: categorias.php");
exit();


?>