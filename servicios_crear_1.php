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
= VARIABLES PARA CONSERVAR DATOS
=========================================*/

$nombre_val = '';
$descripcion_val = '';





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
    = GUARDAR VALORES
    =========================================*/

    $nombre_val = $nombre;
    $descripcion_val = $descripcion;





    /*=========================================
    = VALIDAR CAMPOS
    =========================================*/

    if (empty($nombre)) {


        $mensaje = [
            'tipo' => 'error',
            'texto' => "El campo nombre es obligatorio ❌"
        ];



    } else {



        /*=========================================
        = VALIDAR SERVICIO DUPLICADO
        =========================================*/

        $checkNombre = $conn->prepare(
            "
            SELECT id_servicio
            FROM servicio
            WHERE nombre = ?
            "
        );


        $checkNombre->execute([
            $nombre
        ]);



        $servicioExiste = $checkNombre->fetch(PDO::FETCH_ASSOC);




        if ($servicioExiste) {



            $mensaje = [
                'tipo' => 'error',
                'texto' => "Ya existe un servicio con ese nombre ❌"
            ];



        } else {



            /*=========================================
            = SUBIR IMAGEN
            =========================================*/

            $imagen = "";



            if (
                isset($_FILES['imagen']) &&
                $_FILES['imagen']['error'] === 0
            ) {



                $carpetaDestino = "img_servicios/";



                if (!is_dir($carpetaDestino)) {

                    mkdir(
                        $carpetaDestino,
                        0777,
                        true
                    );

                }




                $nombreArchivo =
                    time() . "_" .
                    basename($_FILES['imagen']['name']);



                $rutaCompleta =
                    $carpetaDestino . $nombreArchivo;




                $extension = strtolower(
                    pathinfo(
                        $rutaCompleta,
                        PATHINFO_EXTENSION
                    )
                );



                $permitidos = [
                    "jpg",
                    "jpeg",
                    "png",
                    "gif",
                    "webp"
                ];




                if (in_array($extension, $permitidos)) {



                    if (
                        move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $rutaCompleta
                        )
                    ) {


                        $imagen = $rutaCompleta;



                    } else {


                        $mensaje = [
                            'tipo' => 'error',
                            'texto' => "Error al subir la imagen ❌"
                        ];

                    }




                } else {



                    $mensaje = [
                        'tipo' => 'error',
                        'texto' => "Formato de imagen no permitido ❌"
                    ];

                }





            } else {



                $mensaje = [
                    'tipo' => 'error',
                    'texto' => "Debe seleccionar una imagen ❌"
                ];

            }







            /*=========================================
            = INSERTAR SERVICIO
            =========================================*/

            if (!$mensaje) {



                $sql = "
                    INSERT INTO servicio
                    (
                        nombre,
                        descripcion,
                        imagen
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?
                    )
                ";



                $stmt = $conn->prepare($sql);




                if (
                    $stmt->execute([
                        $nombre,
                        $descripcion,
                        $imagen
                    ])
                ) {



                    $_SESSION['mensaje'] = [
                        'tipo' => 'success',
                        'texto' =>
                            "☑️ Servicio: $nombre creado correctamente"
                    ];



                    header("Location: servicios.php");
                    exit();




                } else {



                    $mensaje = [
                        'tipo' => 'error',
                        'texto' => "Error al guardar el servicio ❌"
                    ];

                }



                $stmt->closeCursor();



            }



        }



        $checkNombre->closeCursor();



    }


}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicios - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/servicios_2.png" type="image/x-icon">
    <link rel="stylesheet" href="css/servicios_crear_1.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->
    <script defer src="js/servicios_crear.js"></script>
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

                <h1>Crear Servicio - WAVE</h1>

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
            🟢 Ingrese los datos
        </h2>

                        <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">

                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-save"></i> Crear 
                                </button>

                                <a href="servicios.php" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                            </div>


                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label>Descripción:</label>
                                <input type="text" name="descripcion">
                            </div>    

                          

                            <div class="form-group">
                                <label>Perfil:</label>

                                <input 
                                    type="file" 
                                    name="imagen" 
                                    id="imagen"
                                    accept="image/*"
                                    required
                                >

                                <!-- Vista previa -->
                                <div class="preview-container">
                                    <img 
                                        id="preview" 
                                        src=""
                                        alt="Vista previa"
                                       
                                    >
                                </div>
                            </div>

                            

                            

                        </form>

    </div>





    





   
</body>
</html>