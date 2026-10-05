<?php

include("auth.php");      // Protege la página
include("conexion.php");  // Conexión SQLite


/*========================================== 
            DATOS DE SESIÓN
============================================*/

$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1     = $_SESSION['name'];
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';





/*=========================================
= MENSAJE
=========================================*/

$mensaje = null;


if(isset($_SESSION['mensaje'])){

    $mensaje = $_SESSION['mensaje'];

    unset($_SESSION['mensaje']);

}





/*=========================================
= VALIDAR ID
=========================================*/

if(!isset($_GET['id']) || empty($_GET['id'])){


    header("Location: usuarios.php");

    exit();

}



$id = (int)$_GET['id'];

$mensaje = null;





/*=========================================
= OBTENER USUARIO
=========================================*/


$sql = "

SELECT *

FROM usuario

WHERE id_usuario = ?

";



$stmt = $conn->prepare($sql);



$stmt->execute([

    $id

]);



$usuario = $stmt->fetch(PDO::FETCH_ASSOC);





if(!$usuario){


    header("Location: usuarios.php");

    exit();

}





$nombre_val = $usuario['nombre'];

$name_val   = $usuario['name'];

$imagen_val = $usuario['imagen'];





/*=========================================
= DATOS DE SESIÓN
=========================================*/

$nombreSesion = $_SESSION['nombre'];

$nameSesion   = $_SESSION['name'];







/*=========================================
= ACTUALIZAR
=========================================*/

if(isset($_POST['guardar'])){


    $nombre = htmlspecialchars(

        trim($_POST['nombre']),

        ENT_QUOTES,

        'UTF-8'

    );



    $password = trim($_POST['password']);



    $name = htmlspecialchars(

        trim($_POST['name']),

        ENT_QUOTES,

        'UTF-8'

    );



    $imagen = $imagen_val;






    /*=========================================
    = VALIDAR DUPLICADO
    =========================================*/


    $check = $conn->prepare("

        SELECT id_usuario

        FROM usuario

        WHERE nombre = ?

        AND id_usuario != ?

    ");




    $check->execute([

        $nombre,

        $id

    ]);




    $usuarioExiste = $check->fetch(PDO::FETCH_ASSOC);





    if($usuarioExiste){


        $mensaje = [

            "tipo" => "error",

            "texto" => "❌ Ese usuario ya existe."

        ];



    }else{





        /*=========================================
        = CONTRASEÑA
        =========================================*/


        if(!empty($password)){


            $passwordHash = password_hash(

                $password,

                PASSWORD_DEFAULT

            );


        }else{


            $passwordHash = $usuario['password'];

        }







        /*=========================================
        = SUBIR NUEVA IMAGEN
        =========================================*/


        if(

            isset($_FILES['imagen']) &&

            $_FILES['imagen']['error'] == 0

        ){


            $carpeta = "img_usuarios/";



            if(!is_dir($carpeta)){


                mkdir(

                    $carpeta,

                    0777,

                    true

                );


            }





            $nombreArchivo =

                time() . "_" .

                basename(

                    $_FILES['imagen']['name']

                );



            $ruta = $carpeta . $nombreArchivo;






            if(

                move_uploaded_file(

                    $_FILES['imagen']['tmp_name'],

                    $ruta

                )

            ){



                // borrar imagen anterior

                if(

                    !empty($usuario['imagen']) &&

                    file_exists($usuario['imagen'])

                ){


                    unlink($usuario['imagen']);

                }



                $imagen = $ruta;


            }



        }







        /*=========================================
        = UPDATE SQLITE
        =========================================*/


        $update = "

            UPDATE usuario

            SET

                nombre = ?,

                password = ?,

                name = ?,

                imagen = ?

            WHERE id_usuario = ?

        ";





        $stmtUpdate = $conn->prepare($update);






        if(

            $stmtUpdate->execute([


                $nombre,

                $passwordHash,

                $name,

                $imagen,

                $id


            ])

        ){



            $_SESSION['mensaje'] = [


                "tipo" => "success",

                "texto" => "🔄 Perfil: Configurado correctamente."

            ];



            header("Location: logout_2.php");

            exit();





        }else{


            $mensaje = [

                "tipo" => "error",

                "texto" => "❌ Error al actualizar el perfil."

            ];


        }



    }



}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar perfil - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/servicios_3.png" type="image/x-icon">
    <link rel="stylesheet" href="css/configuracion_1.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->  
    <script defer src="js/usuarios_editar.js"></script>
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

                <h1>Configurar perfil - WAVE</h1>

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
                            <a href="configuracion.php?id=<?= $id_usuario ?>">
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
            🟢 Configure su perfil
        </h2>

                        <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">
                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-save"></i> Guardar 
                                </button>
                                <a href="perfil.php?id=<?= $id_usuario ?>" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>


                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($name_val) ?>">
                            </div>

                            <div class="form-group">
                                <label>Usuario:</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre_val) ?>" required>
                            </div>    

                            <div class="form-group">
                                <label>Contraseña:</label>
                                <input type="password" name="password" 
                                       placeholder="Dejar vacío para conservar" id="password" >

                                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                               
                            </div>
                          

                            <div class="form-group">
                                <label>Perfil:</label>

                                <input 
                                    type="file" 
                                    name="imagen" 
                                    id="imagen"
                                    accept="image/*"
                                    
                                >

                                <!-- Vista previa 
                                <div class="preview-container">
                                    <img 
                                        id="preview" 
                                        src="<?= !empty($usuario['imagen']) ? htmlspecialchars($usuario['imagen']) : 'img/user.png' ?>"
                                        alt="Vista previa"
                                        style="display:block;"
                                    >
                                </div>
                                -->
                                <div class="preview-container">

                                    <!-- Imagen actual -->
                                    <div class="preview-box">

                                        <span class="preview-title">
                                            Actual
                                        </span>

                                        <img
                                            id="previewActual"
                                            src="<?= !empty($usuario['imagen']) ? htmlspecialchars($usuario['imagen']) : 'img/user.png' ?>"
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