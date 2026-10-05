<?php
include("auth.php");      // Protege la página
include("conexion.php");  // Conexión BD

$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1       = $_SESSION['name'] ?? 'Usuario';
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';



// Contar usuarios
$sqlTotal = "SELECT COUNT(*) FROM usuario";
$totalUsuarios = $conn->query($sqlTotal)->fetchColumn();


// Contar categorías
$sqlTotal = "SELECT COUNT(*) FROM categoria";
$totalCategorias = $conn->query($sqlTotal)->fetchColumn();


// Contar servicio
$sqlTotal = "SELECT COUNT(*) FROM servicio";
$totalServicios = $conn->query($sqlTotal)->fetchColumn();


// Contar cuenta
$sqlTotal = "SELECT COUNT(*) FROM cuenta";
$totalCuentas = $conn->query($sqlTotal)->fetchColumn();

?>

<!DOCTYPE html>
<html lang="es">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - WAVE</title>

    <link rel="shortcut icon" href="img2/maiin.png" type="image/x-icon">

    <!--<link rel="stylesheet" href="styles.css">-->

    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->

    <script defer src="js/dashboard.js"></script>


</head>



<style>
    

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

    <header>
        <div class="top-line"></div>

        <div class="header-left">
            <a href="dashboard.php" class="logo-link">
                <img src="img2/maiin.png" class="logo" alt="WAVE">
            </a>

            <div class="titulo">
                <p>Wave</p>
                <h3>Dashboard</h3>
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



    <div class="grid">

        <a href="usuarios.php" class="card">

            <div class="imagen">
            <img src="img2/usuarios-2.png">
            </div>

            <div class="nombre">Usuarios</div>

            <div class="info"> <strong> (<?= $totalUsuarios ?>) </strong> </div>

        </a>

        <a href="categorias.php" class="card">

            <div class="imagen">
            <img src="img2/categorias.ico">
            </div>

            <div class="nombre">Categorías</div>

            <div class="info">   <strong> (<?= $totalCategorias ?>) </strong> </div>

        </a>

        <a href="servicios.php" class="card">

            <div class="imagen">
            <img src="img2/categorias-2.ico">
            </div>

            <div class="nombre">Servicios</div>

            <div class="info"> <strong> (<?= $totalServicios ?>) </strong> </div>

        </a>



        <a href="cuentas.php" class="card">

            <div class="imagen">
            <img src="img2/cuentas-2.png">
            </div>

            <div class="nombre">Cuentas</div>

            <div class="info"> <strong> (<?= $totalCuentas ?>) </strong> </div>

        </a>



        <!--

            <a href="logs.php" class="card">

            <div class="imagen">
            <img src="img2/categorias-2.ico">
            </div>

            <div class="nombre">Redes</div>

            <div class="info"> <strong> (<?= $totalServicios ?>) </strong> </div>

        </a>

        -->


        <a href="logout.php" class="card">

            <div class="imagen">
            <img src="img2/salir.png">
            </div>

            <div class="nombre">Cerrar</div>

            <div class="info"><strong>Sesión.</strong></div>

        </a>



    </div>

</body>
</html>