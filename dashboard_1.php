<?php
include("auth.php");      // Protege la página
//include("conexion.php");  // Conexión BD

$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1       = $_SESSION['name'] ?? 'Usuario';
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WAVE</title>
    <link rel="shortcut icon" href="img/menu.png" type="image/x-icon">
    <link rel="stylesheet" href="css/dashboard.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->
    <script defer src="js/dashboard.js"></script>
</head>









<body>



    <header>
        <div class="top-line"></div> <!-- Línea semitransparente superior -->

        <div class="header-container">

                <div class="logo">
                    <a href="dashboard.php">
                        <img src="img/maiin.png" alt="Logo">
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

        <!--  CARD de USUARIOS-->
        <div class="card" id="card1">
            <a href="usuarios.php" class="card-opciones">

                <!-- Lado izquierdo (imagen) -->
                <div class="card-img">
                    <img src="img_iconos/walter.jpg" alt="producto">
                </div>

                <!-- Lado derecho -->
                <div class="card-content">
                    <h3 style="font-family: Righteous, sans-serif; ">Usuarios</h3>
                    <p><strong>Sesiónes.</strong></p>
                  
                    <div class="ver-mas">
                        <div class="icon-circle2">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <strong>Ver más.</strong>
                    </div>
                </div>

            </a>
        </div>


        <!--  CARD de CATEGORIAS-->
        <div class="card" id="card2">
            <a href="categorias.php" class="card-opciones">

                <!-- Lado izquierdo (imagen) -->
                <div class="card-img">
                    <img src="img_iconos/categoria.png" alt="producto">
                </div>

                <!-- Lado derecho -->
                <div class="card-content">
                    <h3 style="font-family: Righteous, sans-serif; ">Categorias</h3>
                    <p><strong>Clase.</strong></p>
                  
                    <div class="ver-mas">
                        <div class="icon-circle2">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <strong>Ver más.</strong>
                    </div>
                </div>

            </a>
        </div>



        <!--  CARD de SERVICIOS-->
        <div class="card" id="card3">
            <a href="servicios.php" class="card-opciones">

                <!-- Lado izquierdo (imagen) -->
                <div class="card-img">
                    <img src="img_iconos/servicios_2.png" alt="producto">
                </div>

                <!-- Lado derecho -->
                <div class="card-content">
                    <h3 style="font-family: Righteous, sans-serif; ">Servicios</h3>
                    <p><strong>Apps.</strong></p>
                  
                    <div class="ver-mas">
                        <div class="icon-circle2">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <strong>Ver más.</strong>
                    </div>
                </div>

            </a>
        </div>



        <!--  CARD de CUENTAS-->
        <div class="card" id="card4">
            <a href="cuentas.php" class="card-opciones">

                <!-- Lado izquierdo (imagen) -->
                <div class="card-img">
                    <img src="img_iconos/cuentas_2.jpg" alt="producto">
                </div>

                <!-- Lado derecho -->
                <div class="card-content">
                    <h3 style="font-family: Righteous, sans-serif; ">Cuentas</h3>
                    <p><strong>Logeado.</strong></p>
                  
                    <div class="ver-mas">
                        <div class="icon-circle2">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <strong>Ver más.</strong>
                    </div>
                </div>

            </a>
        </div>

        <!--  CARD de CERRAR-->
        <div class="card" id="card5">
            <a href="logout.php" class="card-opciones">

                <!-- Lado izquierdo (imagen) -->
                <div class="card-img">
                    <img src="img_iconos/cerrar.png" alt="producto">
                </div>

                <!-- Lado derecho -->
                <div class="card-content">
                    <h3 style="font-family: Righteous, sans-serif; ">Cerrar</h3>
                    <p><strong>Sesión.</strong></p>
                  
                    <div class="ver-mas">
                        <div class="icon-circle2" style="background: #b7b7b7;">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </div>
                        <strong>Salir.</strong>
                    </div>
                </div>

            </a>
        </div>

    </div>












<!--
    <script>
        const profileBtn = document.querySelector('.profile-btn');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        const arrowIcon = profileBtn.querySelector('.arrow');

        profileBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('active');
            arrowIcon.classList.toggle('rotate');
        })

        window.addEventListener('click', (e) => {
            const clickedOutside =
            !profileBtn.contains(e.target) &&
            !dropdownMenu.contains(e.target);

            if (clickedOutside) {
                dropdownMenu.classList.remove('active');
                arrowIcon.classList.remove('rotate');
            }
        })

    </script>
-->
</body>
</html>