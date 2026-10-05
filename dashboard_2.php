<?php
include("auth.php");      // Protege la página
include("conexion.php");  // Conexión BD

$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1       = $_SESSION['name'];
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WAVE</title>
    <link rel="shortcut icon" href="img/menu.png" type="image/x-icon">
    <link rel="stylesheet" href="css/dashboard_2.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 


    <script defer src="js/dashboard.js"></script>
</head>









<body>



    <header>
        <div class="top-line"></div> <!-- Línea semitransparente superior -->

        <div class="header-container">

                <div class="logo">
                    <a href="dashboard.php">
                        <img src="img/main.png" alt="Logo">
                    </a>
                </div>

                <h1>Dashboard - WAVE</h1>

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
                            <a href="perfil.php">
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
                            <a href="logout.php" style="color:red; ">
                                <i class="fas fa-sign-out-alt"> </i>
                                <strong>Cerrar Sesión</strong>
                            </a>
                        </li>
                    </ul>
                </div>

                
        </div>


    </header>


    <div class="grid-container">

        <a href="usuarios.php" class="card-opciones">
            <div class="texto">
                <h3>Usuarios</h3>
                <p>Sesiónes.</p>
            </div>
            <img style="width: 50px; height: 50px" src="img_iconos/user.png" alt="logo">
            <div class="learn-more">
                <div class="icon-circle2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
                Ver más.
            </div>
        </a>


        <a href="categorias.php" class="card-opciones">
            <div class="texto">
                <h3>Categorias</h3>
                <p>Clase.</p>
            </div>
            <img style="width: 50px; height: 50px" src="img_iconos/categoria.png" alt="logo">
            <div class="learn-more">
                <div class="icon-circle2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
                Ver más.
            </div>
        </a>

        <a href="servicios.php" class="card-opciones">
            <div class="texto">
                <h3>Servicios</h3>
                <p>Apps.</p>
            </div>
            <img style="width: 50px; height: 50px" src="img_iconos/servicios_2.png" alt="logo">
            <div class="learn-more">
                <div class="icon-circle2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
                Ver más.
            </div>
        </a>

        <a href="cuentas.php" class="card-opciones">
            <div class="texto">
                <h3>Cuentas</h3>
                <p>Logeado.</p>
            </div>
            <img style="width: 50px; height: 50px" src="img_iconos/cuentas.png" alt="logo">
            <div class="learn-more">
                <div class="icon-circle2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
                Ver más.
            </div>
        </a>

        <a href="logout.php" class="card-opciones">
            <div class="texto">
                <h3>Cerrar</h3>
                <p>Sesión.</p>
            </div>
            <img style="width: 50px; height: 50px" src="img_iconos/exit_2.png" alt="logo">
            <div class="learn-more">
                <div class="icon-circle2">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                Salir.
            </div>
        </a>

    </div>
    
</body>
</html>