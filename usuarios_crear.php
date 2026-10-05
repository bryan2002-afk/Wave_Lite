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


if (isset($_SESSION['mensaje'])) {

    $mensaje = $_SESSION['mensaje'];

    unset($_SESSION['mensaje']);

}



/*=========================================
= VARIABLES PARA CONSERVAR DATOS
=========================================*/

$nombre_val = '';
$name_val = '';




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


    $password = trim($_POST['password']);



    $name = htmlspecialchars(
        trim($_POST['name']),
        ENT_QUOTES,
        'UTF-8'
    );



    /*=========================================
    = GUARDAR VALORES
    =========================================*/

    $nombre_val = $nombre;

    $name_val = $name;




    /*=========================================
    = VALIDAR CAMPOS
    =========================================*/

    if(empty($nombre) || empty($password)){


        $mensaje = [

            'tipo'=>'error',

            'texto'=>"Los campos Usuario y Contraseña son obligatorios ❌"

        ];



    }else{



        /*=========================================
        = VALIDAR USUARIO DUPLICADO SQLITE
        =========================================*/


        $checkNombre = $conn->prepare(

            "SELECT id_usuario
             FROM usuario
             WHERE nombre = ?"

        );


        $checkNombre->execute([

            $nombre

        ]);



        $usuarioExiste = $checkNombre->fetch();



        if($usuarioExiste){



            $mensaje = [

                'tipo'=>'error',

                'texto'=>"Ya existe una sesión con ese usuario ❌"

            ];



        }else{



            /*=========================================
            = CIFRAR CONTRASEÑA
            =========================================*/

            $passwordHash = password_hash(

                $password,

                PASSWORD_DEFAULT

            );





            /*=========================================
            = SUBIR IMAGEN
            =========================================*/

            $imagen = "";



            if(

                isset($_FILES['imagen']) &&

                $_FILES['imagen']['error'] === 0

            ){



                $carpetaDestino = "img_usuarios/";



                if(!is_dir($carpetaDestino)){

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

                    $carpetaDestino .

                    $nombreArchivo;



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




                if(in_array($extension,$permitidos)){



                    if(move_uploaded_file(

                        $_FILES['imagen']['tmp_name'],

                        $rutaCompleta

                    )){


                        $imagen = $rutaCompleta;



                    }else{


                        $mensaje = [

                            'tipo'=>'error',

                            'texto'=>"Error al subir la imagen ❌"

                        ];

                    }



                }else{


                    $mensaje = [

                        'tipo'=>'error',

                        'texto'=>"Formato de imagen no permitido ❌"

                    ];

                }



            }else{


                $mensaje = [

                    'tipo'=>'error',

                    'texto'=>"Debe seleccionar una imagen ❌"

                ];

            }






            /*=========================================
            = INSERTAR USUARIO SQLITE
            =========================================*/

            if(!$mensaje){



                $sql = "

                INSERT INTO usuario

                (

                    nombre,

                    password,

                    name,

                    imagen

                )

                VALUES

                (?,?,?,?)

                ";



                $stmt = $conn->prepare($sql);



                if(

                    $stmt->execute([

                        $nombre,

                        $passwordHash,

                        $name,

                        $imagen

                    ])

                ){



                    $_SESSION['mensaje'] = [

                        'tipo'=>'success',

                        'texto'=>

                        "☑️\nUsuario: $nombre creado correctamente."

                    ];



                    header("Location: usuarios.php");

                    exit();



                }else{



                    $mensaje = [

                        'tipo'=>'error',

                        'texto'=>"Error al guardar el usuario ❌"

                    ];

                }



            }



        }



    }



}



?>


<!DOCTYPE html>
<html lang="es">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear Usuarios - WAVE</title>

    <link rel="shortcut icon" href="img2/usuarios-2.png" type="image/x-icon">

    <!--<link rel="stylesheet" href="styles.css">-->
    <link rel="stylesheet" href="css/u_crear.css">

    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->

    <script defer src="js/usuarios_crear.js"></script>


</head>



<style>
    /*@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');*/


    div:where(.swal2-container) .swal2-timer-progress-bar{
        background: linear-gradient(90deg, #00c853, #64dd17);
        height: 6px;
        border-radius: 10px;
    }

    /* Fondo de la ventana */
    .swal2-popup{
        border-radius: 18px;
        padding: 2rem;
        box-shadow: 0 15px 40px rgba(0,0,0,.25);
    }

    /* Título */
    .swal2-title{
        font-family: "Oswald", sans-serif;
        font-size: 28px;
        font-weight: 600;
    }

    /* Texto */
    .swal2-html-container{
        font-family: "Oswald", sans-serif;
        font-size: 18px;
    }

    /* Icono */
    .swal2-icon{
        border-width: 4px !important;
    }


    

    

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial, Helvetica, sans-serif;
    }

    body{
        background: #181818;
        color:#fff;
    }

    /*======================
            HEADER
    ======================*/

    /*
    font-family: "Lilita One", cursive;
    font-family: "Inter", sans-serif;
    font-family: "Righteous", sans-serif;
    */

    header{
        position:sticky;
        top:0;
        z-index:1000;

        display:flex;
        justify-content:space-between;
        align-items:center;

        min-height:72px;
        padding:12px 24px;

        background: #232323; /* #232323 */
        border-bottom:1px solid #303030;
    }

    .top-line{
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:5px;
        /*background:linear-gradient(90deg,#0099ff,#00c8ff,#00e0ff);*/
        background:linear-gradient(to bottom,#a24b8f,#4d246b);
        z-index:9999;
    }

    .header-left{
        display:flex;
        align-items:center;
        gap:14px;
        min-width:0;
    }

    .logo-link{
        display:flex;
        align-items:center;
        text-decoration:none;
    }

    .logo{
        width:clamp(34px,4vw,42px);
        height:clamp(34px,4vw,42px);
        object-fit:contain;
        cursor:pointer;
        transition:.3s;
    }

    .logo:hover{
        transform:scale(1.08);
    }

    .titulo{
        display:flex;
        flex-direction:column;
        justify-content:center;
        min-width:0;
    }

    .titulo p{
        margin:0;
        color:#b5b5b5;
        font-family:"Inter", sans-serif;
        font-size:clamp(12px,1.8vw,15px);
    }

    .titulo h3{
        
        margin:0;
        line-height:1.1;
        font-family:"Righteous", sans-serif;
        font-size:clamp(20px,3vw,28px);
    }

    .icono{
        font-size:28px;
        cursor:pointer;
    }

    /*======================
        PERFIL DE USUARIO
    ======================*/

    .profile-container{
        position:relative;
        flex-shrink:0;
    }

    .profile-btn{
        display:flex;
        align-items:center;
        gap:12px;

        min-width:185px;
        max-width:230px;

        padding:8px 12px;

        background:#2b2b2b;
    /* border:1px solid #3c3c3c;
        border:1px solid #f425ca;*/
        border:1px solid #f425ca;
        border-radius:12px;

        color:#fff;
        cursor:pointer;

        transition:.25s;
    }

    .profile-btn:hover{
        /*
        background:#353535;
        border-color:#555;
        */

    
    transform:translateY(-2px);

        box-shadow:
            0 15px 30px rgba(0,0,0,.10);
        
    }

    .profile-btn img{
        width:42px;
        height:42px;
        border-radius:50%;
        object-fit:cover;
    /* border:2px solid #4d4d4d;*/
        border:2px solid #f425ca;
    }

    .profile-name{
        flex:1;
        font-size:15px;
        font-weight:600;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .arrow{
        margin-left:auto;
        font-size:28px;
        transition:transform .3s;
        color: #f425ca;
    }

    .arrow.rotate{
        transform:rotate(180deg);
    }

    /*======================
        MENÚ DESPLEGABLE
    ======================*/

    .dropdown-menu{
        position:absolute;
        top:calc(100% + 10px);
        right:0;

        width:210px;

        background:#2b2b2b;
        border:1px solid #3c3c3c;
        border-radius:12px;

        list-style:none;
        overflow:hidden;

        display:none;

        box-shadow:0 10px 25px rgba(0,0,0,.35);

        z-index:1000;
    }

    .dropdown-menu.active{
        display:block;
    }

    .dropdown-menu li{
        border-bottom:1px solid #3c3c3c;
    }

    .dropdown-menu li:last-child{
        border-bottom:none;
    }

    .dropdown-menu li a{
        display:flex;
        align-items:center;
        gap:12px;

        padding:13px 15px;

        text-decoration:none;
        color:#fff;
        font-size:15px;

        transition:.25s;
    }

    .dropdown-menu li a:hover{
        background:#3a3a3a;
    }

    .dropdown-menu li a i{
        font-size:20px;
        color:#9ec5ff;
    }

    .dropdown-user{
        display:none;
        align-items:center;
        gap:12px;
        padding:15px;
        border-bottom:1px solid #3c3c3c;
    }

    .dropdown-user img{
        width:42px;
        height:42px;
        border-radius:50%;
        object-fit:cover;
    }

    .dropdown-user span{
        font-size:16px;
        font-weight:600;
    }



    /*======================
            GRID
    ======================*/

    .grid{
        padding:25px;
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(170px,1fr));
        gap:30px;
    }

    /*======================
            CARD
    ======================*/

    .card{
        text-decoration:none;
        color:#fff;
        text-align:center;
    }

    .imagen{
        width:120px;
        height:120px;

        margin:auto;

        border:1px solid #666;
        border-radius:22px;

        display:flex;
        justify-content:center;
        align-items:center;

        transition:.3s;
    }

    .imagen img{
        width:90px;
        height:90px;
        object-fit:contain;
    }

    .card:hover .imagen{
        background:#2e2e2e;
        transform:translateY(-5px);
    }

    .nombre{
        margin-top:15px;
        font-size:20px;
        font-family:"Righteous", sans-serif;
    }

    .info{
        margin-top:5px;
        color:#b5b5b5;
        font-size:18px;
    }

    /*======================
        TABLETS
    ======================*/

    @media (max-width:900px){

        header{
            padding:10px 16px;
        }

        .profile-btn{
            min-width:170px;
        }

        .grid{
            grid-template-columns:repeat(3,1fr);
            gap:22px;
        }

    }

    /*======================
        TELÉFONOS
    ======================*/

    @media (max-width:600px){

        header{
            padding:10px 12px;
            gap:10px;
        }

        .header-left{
            gap:10px;
        }

        .logo{
            width:36px;
            height:36px;
        }

        .titulo h3{
            font-size:20px;
        }

        .titulo p{
            font-size:12px;
        }

        /*======================
            PERFIL
        ======================*/

        .profile-btn{
            min-width:auto;
            width:auto;
            padding:6px 10px;
            gap:8px;
        }

        .profile-btn img{
            width:36px;
            height:36px;
        }

        /* Oculta el nombre del botón */
        .profile-name{
            display:none;
        }

        .arrow{
            margin-left:0;
            font-size:24px;
        }

        .dropdown-menu{
            width:190px;
        }

        /* Muestra el encabezado del menú */
        .dropdown-user{
            display:flex;
            align-items:center;
            gap:12px;
            padding:15px;
            background:#313131;
            border-bottom:1px solid #454545;
        }

        .dropdown-user img{
            width:42px;
            height:42px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid #4d4d4d;
        }

        .dropdown-user span{
            font-size:16px;
            font-weight:600;
            color:#fff;
        }

        /*======================
            GRID
        ======================*/

        .grid{
            grid-template-columns:repeat(3,1fr);
            gap:18px;
            padding:18px;
        }

        .imagen{
            width:95px;
            height:95px;
        }

        .imagen img{
            width:70px;
            height:70px;
        }

        .nombre{
            font-size:18px;
        }

        .info{
            font-size:15px;
        }

    }



    /*======================
    TELÉFONOS PEQUEÑOS
    ======================*/

    @media (max-width:420px){

        header{
            padding:8px 10px;
        }

        .titulo h3{
            font-size:18px;
        }

        .titulo p{
            font-size:11px;
        }

        .logo{
            width:34px;
            height:34px;
        }

        .profile-btn{
            padding:6px 8px;
        }

        .profile-btn img{
            width:34px;
            height:34px;
        }

        .dropdown-menu{
            width:180px;
        }

        .grid{
            gap:16px;
            padding:16px;
        }

        .imagen{
            width:85px;
            height:85px;
        }

        .imagen img{
            width:62px;
            height:62px;
        }

        .nombre{
            font-size:16px;
        }

        .info{
            font-size:14px;
        }

    }


</style>

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
        <div class="top-line"></div>

        <div class="header-left">
            <a href="dashboard.php" class="logo-link">
                <img src="img2/maiin.png" class="logo" alt="WAVE">
            </a>

            <div class="titulo">
                <p>Wave</p>
                <h3>Crear/Usuarios</h3>
            </div>
        </div>


  

        <div class="profile-container">

            <button class="profile-btn" type="button" aria-expanded="false" aria-label="Menú de usuario">
                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Foto de perfil de Bryan">
                <span class="profile-name"><?php echo htmlspecialchars($nombre); ?></span>
                <i class="ri-arrow-drop-down-line arrow" aria-hidden="true"></i>
            </button>

            <ul class="dropdown-menu">

                <li class="dropdown-user">
                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Foto de perfil">
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($nombre); ?></span>
                        <small><?php echo htmlspecialchars($name_1); ?></small>
                    </div>
                </li>

                <li>
                    <a href="perfil.php?id=<?= $id_usuario ?>">
                        <i class="ri-user-line"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>

                <li>
                    <a href="configuracion.php?id=<?= $id_usuario ?>">
                        <i class="ri-settings-3-line"></i>
                        <span>Configuración</span>
                    </a>
                </li>

                <li>
                    <a href="logout.php">
                        <i class="ri-logout-box-r-line"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </li>
            </ul>

        </div>



    </header>


    <div class="pizarra">

        <h2 class="titulo-pizzarra">
            🟢 Ingrese los datos
        </h2>

                        <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">
                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-save"></i> Crear 
                                </button>
                                <a href="usuarios.php" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>


                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" name="name">
                            </div>

                            <div class="form-group">
                                <label>Usuario:</label>
                                <input type="text" name="nombre" required>
                            </div>    

                            <div class="form-group">
                                <label>Contraseña:</label>
                                <input type="password" name="password" id="password" required>
                                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                               
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

                            

                            <!--
                            <div class="form-buttons">
                                <button type="submit" name="guardar">
                                    <i class="fas fa-save"></i> Crear 
                                </button>
                                <a href="usuarios.php" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                            -->

                        </form>

    </div>


</body>
</html>