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
= CONSULTAR CATEGORIAS
=========================================*/

$sql = "
    SELECT 
        id_categoria,
        nombre,
        descripcion
    FROM categoria
    ORDER BY id_categoria DESC
";


$stmt = $conn->prepare($sql);

$stmt->execute();



/*=========================================
= GUARDAR DATOS EN ARRAY
=========================================*/

$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/categoria.png" type="image/x-icon">
    <link rel="stylesheet" href="css/categorias_1.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->  

    <script defer src="js/categorias.js"></script>
</head>


<!--
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
        /*margin:0;
        padding:0;*/
        box-sizing:border-box;
        /*font-family:'Poppins',sans-serif;*/
    }

    /*=========================================
    =            HEADER PRINCIPAL             =
    =========================================*/
    body{
        /*background: rgb(15, 15, 15);*/
        background: #181818;
    
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

    header{

        width:100%;
        position:sticky;
        top:5px;
        z-index:1000;

        background: black;

        backdrop-filter:blur(18px);

        border-bottom:1px solid rgba(0,0,0,.08);

        box-shadow:
            0 8px 25px rgba(0,0,0,.06);

    }

    .header-container{

        width:min(1400px,100%);
        margin:auto;

        display:flex;
        justify-content:space-between;
        align-items:center;

        padding:18px 0;

        gap:30px;
        background: black;

    }

    .logo{

        display:flex;
        align-items:center;

    }

    .logo img{

        width:auto;
        height:65px;

        transition:.35s;

    }

    .logo img:hover{

        transform:scale(1.06);

    }

    .header-container h1{

        flex:1;

        text-align:center;

        font-size:2rem;
        font-weight:700;

        color: #f425ca;/*#0d6efd*/

        margin:0;

        letter-spacing:.5px;

    }

    /*==============================
    =      PERFIL USUARIO          =
    ==============================*/

    .profile-container{

        position:relative;
        font-family:'Poppins',sans-serif;

    }

    .profile-btn{

        display:flex;
        align-items:center;
        gap:14px;

        padding:10px 18px;

        background:#333;

        

        border-radius:16px;

        border:1px solid #f425ca;

        cursor:pointer;

        transition:.35s;

        box-shadow:
            0 5px 15px rgba(0,0,0,.05);

    }

    .profile-btn:hover{

        transform:translateY(-2px);

        box-shadow:
            0 15px 30px rgba(0,0,0,.10);

    }

    .profile-btn img{

        width:46px;
        height:46px;

        border-radius:50%;

        object-fit:cover;

        border:3px solid #f425ca;

    }

    .profile-btn span{

        font-size:.95rem;
        color:white;

    }

    .profile-btn strong{

        font-weight:600;

    }

    .arrow{

        font-size:2rem;

        color:#f425ca;

        transition:.35s;

    }

    .rotate{

        transform:rotate(180deg);

    }

    /*==============================
    =      DROPDOWN MENU           =
    ==============================*/


    .dropdown-menu{

        position:absolute;

        top:72px;
        right:0;

        width:240px;

        padding:8px;

        list-style:none;

        border-radius:18px;

        background:#333;

        border:1px solid #f425ca;

        box-shadow:
            0 15px 40px rgba(0,0,0,.15);

        display:none;

        overflow:hidden;

    }


    .dropdown-menu.active{

        display:block;

        animation:fadeDown .25s ease;

    }

    .dropdown-menu li{

        margin:4px 0;

    }

    .dropdown-menu li a{

        display:flex;
        align-items:center;

        gap:12px;

        padding:14px;

        text-decoration:none;

        color:white;

        border-radius:12px;

        transition:.25s;

    }

    .dropdown-menu li a:hover{

        background:#eef7ff;

        color:#0d6efd;

    }

    .dropdown-menu li a i{

        width:22px;

        text-align:center;

        font-size:18px;

    }

    .dropdown-menu li:last-child a{

        color:#f4706e;

    }

    .dropdown-menu li:last-child a:hover{

        background:#fff1f1;

    }

    /*==============================
    =         ANIMACIÓN            =
    ==============================*/

    @keyframes fadeDown{

        from{

            opacity:0;
            transform:translateY(-10px);

        }

        to{

            opacity:1;
            transform:translateY(0);

        }

    }





    /*
    .pizarra{

        display:grid;

        grid-template-columns:repeat(auto-fit,minmax(340px,1fr));

        gap:30px;
        font-family: "Oswald", sans-serif;
        padding:40px;

    }
    */
    .pizarra{

    width:min(1300px,95%);

    margin:30px auto;

    padding:20px;

    font-family:"Oswald",sans-serif;

    }





    /*=========================================
    =          RESPONSIVE TABLET              =
    =========================================*/
    @media (max-width: 992px){

        .header-container{
            padding:15px;
            gap:15px;
        }

        .header-container h1{
            font-size:1.7rem;
        }

        .pizarra{
            grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            padding:25px;
            gap:20px;
        }

        .card{
            width:100%;
        }

    }


    /*=========================================
    =        RESPONSIVE CELULAR               =
    =========================================*/
    @media (max-width:768px){

        /* Header */

        .header-container{

            flex-direction:column;

            justify-content:center;

            align-items:center;

            text-align:center;

            padding:18px;

        }

        .logo img{
            height:55px;
        }

        .header-container h1{
            font-size:1.5rem;
        }

        /* Perfil */

        .profile-btn{

            width:100%;

            justify-content:center;

            padding:12px;

        }

        .dropdown-menu{

            width:100%;

            right:0;

        }

        /* Cards */

        .pizarra{

            grid-template-columns:1fr;

            padding:20px;

            gap:20px;

        }

        .card{

            width:100%;

        }

        .card-opciones{

            flex-direction:column;

            text-align:center;

            gap:18px;

        }

        .card-img{

            width:90px;

            height:90px;

        }

        .card-img img{

            width:75px;

        }

        .card-content h3{

            font-size:24px;

        }

        .card-content p{

            font-size:14px;

            margin-bottom:18px;

        }

        .ver-mas{

            justify-content:center;

        }

    }


    /*=========================================
    =      CELULARES PEQUEÑOS                 =
    =========================================*/
    @media (max-width:480px){

        .header-container{

            padding:15px;

        }

        .header-container h1{

            font-size:1.25rem;

        }

        .logo img{

            height:48px;

        }

        .profile-btn{

            gap:10px;

            padding:10px;

        }

        .profile-btn img{

            width:40px;

            height:40px;

        }

        .profile-btn span{

            font-size:.85rem;

        }

        .arrow{

            font-size:1.6rem;

        }

        .pizarra{

            padding:15px;

            gap:15px;

        }

        .card{

            border-radius:16px;

        }

        .card-opciones{

            padding:18px;

        }

        .card-img{

            width:80px;

            height:80px;

        }

        .card-img img{

            width:65px;

        }

        .card-content h3{

            font-size:21px;

        }

        .card-content p{

            font-size:13px;

        }

        .icon-circle2{

            width:36px;

            height:36px;

        }

    }
</style>
-->



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

                <h1>Categorías - WAVE</h1>

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


    <div class="botones-arriba">

        <a href="dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Regresar</span>
        </a>

        <a href="categorias_crear.php" class="btn-new">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </a>

    </div>

    
    <div class="pizarra">

        <h2 class="titulo-pizzarra">
            🟢 Datos
        </h2>

            <div class="usuarios-cards">

                <?php if (!empty($categorias)): ?>

                    <?php foreach ($categorias as $row): ?>
                        
                        <?php $id = (int)$row['id_categoria']; ?>

                        <div class="usuario-card">

                           
                            

                            <!-- Datos -->
                            <div class="usuario-info">
                                <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                                <p><strong>Descripción:</strong> <?= htmlspecialchars($row['descripcion']) ?></p>
                                
                                

                            </div>

                            <!-- Acciones -->
                            <div class="usuario-actions">

                                <a href="categorias_editar.php?id=<?= $id ?>" class="btn-edit">
                                    <i class="fas fa-pen"></i>
                                    <span>Editar</span>
                                </a>


                                <a href="categorias_borrar.php?id=<?= $id ?>"
                                class="btn-delete"
                                onclick="return confirm('¿🗑️​ Eliminar : <?= htmlspecialchars($row['nombre']) ?>?')">

                                    <i class="fas fa-trash"></i>
                                    <span>Borrar</span>

                                </a>

                                

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No existen categorías registradas.</p>

                <?php endif; ?>

            </div>

    </div>












   
</body>
</html>