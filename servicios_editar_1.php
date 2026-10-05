<?php
include("auth.php");
include("conexion.php");


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
        "texto" => "ID del servicio inválido ❌"
    ];

    header("Location: servicios.php");
    exit();

}


$id_servicio = (int)$_GET['id'];



/*=========================================
= OBTENER SERVICIO
=========================================*/

$sqlServicio = "
    SELECT
        id_servicio,
        nombre,
        descripcion,
        imagen
    FROM servicio
    WHERE id_servicio = ?
";


$stmt = $conn->prepare($sqlServicio);
$stmt->execute([$id_servicio]);


$servicio = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$servicio) {

    $_SESSION['mensaje'] = [
        "tipo"  => "error",
        "texto" => "El servicio no existe ❌"
    ];

    header("Location: servicios.php");
    exit();

}



/*=========================================
= VARIABLES FORMULARIO
=========================================*/

$nombre_val      = $servicio['nombre'];
$descripcion_val = $servicio['descripcion'];
$imagen_actual   = $servicio['imagen'];




/*=========================================
= PROCESAR FORMULARIO
=========================================*/

if(isset($_POST['guardar'])){


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



    $nombre_val = $nombre;
    $descripcion_val = $descripcion;



    if(empty($nombre)){


        $mensaje = [
            "tipo"=>"error",
            "texto"=>"El campo nombre es obligatorio ❌"
        ];


    }else{



        /*=========================================
        VALIDAR DUPLICADO
        =========================================*/


        $sqlDuplicado="
            SELECT id_servicio
            FROM servicio
            WHERE nombre = ?
            AND id_servicio <> ?
        ";


        $stmtDuplicado=$conn->prepare($sqlDuplicado);

        $stmtDuplicado->execute([
            $nombre,
            $id_servicio
        ]);


        if($stmtDuplicado->fetch()){


            $mensaje=[
                "tipo"=>"error",
                "texto"=>"Ya existe un servicio con ese nombre ❌"
            ];



        }else{



            $imagen=$imagen_actual;



            /*=========================================
            CAMBIAR IMAGEN
            =========================================*/


            if(
                isset($_FILES['imagen']) &&
                $_FILES['imagen']['error']==0
            ){


                $carpeta="img_servicios/";


                if(!is_dir($carpeta)){
                    mkdir($carpeta,0777,true);
                }



                $archivo =
                time()."_".
                basename($_FILES['imagen']['name']);



                $ruta=$carpeta.$archivo;



                $extension=strtolower(
                    pathinfo(
                        $ruta,
                        PATHINFO_EXTENSION
                    )
                );



                $permitidos=[
                    "jpg",
                    "jpeg",
                    "png",
                    "gif",
                    "webp"
                ];



                if(in_array($extension,$permitidos)){



                    if(
                        move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $ruta
                        )
                    ){



                        if(
                            !empty($imagen_actual) &&
                            file_exists($imagen_actual)
                        ){

                            unlink($imagen_actual);

                        }



                        $imagen=$ruta;



                    }else{


                        $mensaje=[
                            "tipo"=>"error",
                            "texto"=>"Error al subir imagen ❌"
                        ];


                    }



                }else{


                    $mensaje=[
                        "tipo"=>"error",
                        "texto"=>"Formato no permitido ❌"
                    ];


                }



            }



            /*=========================================
            ACTUALIZAR
            =========================================*/


            if(!$mensaje){


                $sqlActualizar="
                    UPDATE servicio
                    SET
                        nombre=?,
                        descripcion=?,
                        imagen=?
                    WHERE id_servicio=?
                ";



                $stmtActualizar=$conn->prepare(
                    $sqlActualizar
                );



                $resultado=$stmtActualizar->execute([
                    $nombre,
                    $descripcion,
                    $imagen,
                    $id_servicio
                ]);



                if($resultado){


                    $_SESSION['mensaje']=[
                        "tipo"=>"success",
                        "texto"=>"☑️ Servicio: $nombre_val actualizado correctamente."
                    ];


                    header("Location: servicios.php");
                    exit();



                }else{


                    $mensaje=[
                        "tipo"=>"error",
                        "texto"=>"Error al actualizar el servicio ❌"
                    ];

                }



            }



        }



    }



}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicios - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/servicios_2.png" type="image/x-icon">
    <link rel="stylesheet" href="css/servicios_editar_1.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->
    <script defer src="js/servicios_editar.js"></script>
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

                <h1>Editar Servicio - WAVE</h1>

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


    <!--
    <div class="botones-arriba">

        <a href="dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Regresar</span>
        </a>

        <a href="servicios_crear.php" class="btn-new">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </a>

    </div>
    -->

    <div class="pizarra">

        <h2 class="titulo-pizzarra">
            🟢 Edite los datos
        </h2>

                        <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">

                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-arrows-rotate"></i> Actualizar 
                                </button>
                                
                                <a href="servicios.php" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                            </div>


                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre_val) ?>"required>
                            </div>

                            <div class="form-group">
                                <label>Descripción:</label>
                                <input type="text" name="descripcion" value="<?= htmlspecialchars($descripcion_val) ?>">
                            </div>    

                          

                            <div class="form-group">
                                <label>Perfil:</label>

                                <input 
                                    type="file" 
                                    name="imagen" 
                                    id="imagen"
                                    accept="image/*"
                                    
                                >

                              
                                <div class="preview-container">

                                    <!-- Imagen actual -->
                                    <div class="preview-box">

                                        <span class="preview-title">
                                            Actual
                                        </span>

                                        <img
                                            id="previewActual"
                                            src="<?= !empty($servicio['imagen']) ? htmlspecialchars($servicio['imagen']) : 'img/user.png' ?>"
                                            alt="Imagen actual"
                                        >

                                    </div>

                                    <!-- Imagen nueva -->
                                    <div class="preview-box">

                                        <span class="preview-title">
                                            Nueva
                                        </span>

                                        <img
                                            id="previewNueva"
                                            src=""
                                            alt="Imagen nueva"
                                        >

                                    </div>

                                </div>
                            </div>

                            

                            

                        </form>

    </div>





    





   
</body>
</html>