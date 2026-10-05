<?php
include("auth.php");      // Protege la página
include("conexion.php");  // Conexión SQLite



/*=========================================
= DATOS DE SESIÓN
=========================================*/

$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1     = $_SESSION['name'];
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';



/*=========================================
= MENSAJE
=========================================*/

$mensaje = null;

if (isset($_SESSION['mensaje'])) {

    $mensaje = $_SESSION['mensaje'];

    unset($_SESSION['mensaje']);

}



/*=========================================
= VALIDAR ID
=========================================*/

if (!isset($_GET['id']) || empty($_GET['id'])) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "ID de la categoría inválido ❌"
    ];


    header("Location: categorias.php");
    exit();

}


$id_categoria = (int)$_GET['id'];



/*=========================================
= OBTENER CATEGORÍA
=========================================*/

$sqlCategoria = "
    SELECT
        id_categoria,
        nombre,
        descripcion
    FROM categoria
    WHERE id_categoria = ?
";


$stmt = $conn->prepare($sqlCategoria);


if (!$stmt) {

    die("Error al preparar la consulta.");

}



$stmt->execute([
    $id_categoria
]);



$categoria = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$categoria) {


    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "La categoría no existe ❌"
    ];


    header("Location: categorias.php");
    exit();

}



$stmt->closeCursor();




/*=========================================
= VARIABLES DEL FORMULARIO
=========================================*/

$nombre_val      = $categoria['nombre'];
$descripcion_val = $categoria['descripcion'];




/*=========================================
= PROCESAR FORMULARIO
=========================================*/

if (isset($_POST['guardar'])) {



    /*=========================================
    = SANITIZAR DATOS
    =========================================*/

    $nombre = htmlspecialchars(
        trim($_POST['nombre']),
        ENT_QUOTES,
        'UTF-8'
    );


    $descripcion = htmlspecialchars(
        trim($_POST['descripcion']),
        ENT_QUOTES,
        'UTF-8'
    );




    /*=========================================
    = CONSERVAR VALORES
    =========================================*/

    $nombre_val      = $nombre;
    $descripcion_val = $descripcion;




    /*=========================================
    = VALIDAR CAMPOS
    =========================================*/

    if (empty($nombre)) {



        $mensaje = [
            "tipo"  => "error",
            "texto" => "❌ El campo nombre es obligatorio."
        ];



    } else {



        /*=========================================
        = VALIDAR NOMBRE DUPLICADO
        =========================================*/

        $sqlDuplicado = "
            SELECT id_categoria
            FROM categoria
            WHERE nombre = ?
            AND id_categoria <> ?
        ";


        $stmtDuplicado = $conn->prepare($sqlDuplicado);



        $stmtDuplicado->execute([
            $nombre,
            $id_categoria
        ]);



        $duplicado = $stmtDuplicado->fetch(PDO::FETCH_ASSOC);




        if ($duplicado) {



            $mensaje = [
                "tipo"  => "error",
                "texto" => "🚫 Categoría: '$nombre' existente."
            ];



        } else {



            /*=========================================
            = ACTUALIZAR CATEGORÍA
            =========================================*/


            $sqlActualizar = "
                UPDATE categoria
                SET
                    nombre = ?,
                    descripcion = ?
                WHERE id_categoria = ?
            ";



            $stmtActualizar = $conn->prepare($sqlActualizar);



            if (!$stmtActualizar) {



                $mensaje = [
                    "tipo"  => "error",
                    "texto" => "Error al preparar la consulta."
                ];



            } else {



                if (
                    $stmtActualizar->execute([
                        $nombre,
                        $descripcion,
                        $id_categoria
                    ])
                ) {



                    $_SESSION['mensaje'] = [
                        "tipo"  => "success",
                        "texto" => "🔄 Categoría: '$nombre' actualizada."
                    ];



                    header("Location: categorias.php");
                    exit();



                } else {



                    $mensaje = [
                        "tipo"  => "error",
                        "texto" => "❌ Error al actualizar la categoría."
                    ];

                }



                $stmtActualizar->closeCursor();



            }


        }


        $stmtDuplicado->closeCursor();



    }


}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categorías - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/categoria.png" type="image/x-icon">
    <link rel="stylesheet" href="css/categorias_crear.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->

    <script defer src="js/categorias_crear.js"></script>
</head>









<body>

    <!-- Mensaje Flotante -->    
    <script>
    window.addEventListener('DOMContentLoaded', () => {

        // Usar la variable $mensaje que ya definimos en PHP
        const mensaje = <?php echo $mensaje ? json_encode($mensaje) : 'null'; ?>;

        if (mensaje) {

            Swal.fire({
                icon: mensaje.tipo,
                title: mensaje.tipo === "success" ? "¡Correcto!" : "¡Error!",
                text: mensaje.texto,
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
            });

        }

    });
    </script>

    <header>
        <div class="top-line"></div> <!-- Línea semitransparente superior -->

        <div class="header-container">

                <div class="logo">
                    <a href="dashboard.php">
                        <img src="img/maiin.png" alt="Logo">
                    </a>
                </div>

                <h1>Editar Categoría - WAVE</h1>

                <div class="profile-container">

                    <div class="profile-btn">
                        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Perfil">
                        <span> 
                            <strong><?php echo htmlspecialchars($name_1); ?></strong> 
                        </span>
                        <i class='ri-arrow-drop-down-line icon arrow'></i>
                    </div>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="perfil.php?id=<?= $id_usuario ?>">
                                <i class="fas fa-user-circle"></i>
                                <strong>Mi Perfil</strong>
                            </a>
                        </li>

                        <li>
                            <a href="configuracion.php">
                                <i class="fas fa-gear"></i>
                                <strong>Configuración</strong>
                            </a>
                        </li>

                        <li>
                            <a href="logout.php" style="color: #f22320; ">
                                <i class="fas fa-sign-out-alt"> </i>
                                <strong>Cerrar Sesión</strong>
                            </a>
                        </li>
                    </ul>
                </div>

                
        </div>
        

    </header>

    
    <div class="pizarra">

        <h2 class="titulo-pizzarra">
            🟢 Edite los datos
        </h2>

                        <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">

                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-arrows-rotate"></i> Actualizar 
                                </button>
                                
                                <a href="categorias.php" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                            </div>


                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre_val); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Descripción:</label>
                                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($descripcion_val); ?>">
                            </div>    

                          


                            

                           

                        </form>

    </div>












   
</body>
</html>