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
= CONSULTAR SERVICIOS
=========================================*/

$sql = "
    SELECT 
        id_servicio,
        nombre,
        descripcion,
        imagen
    FROM servicio
    ORDER BY id_servicio DESC
";



$stmt = $conn->prepare($sql);

$stmt->execute();




/*=========================================
= GUARDAR DATOS EN ARRAY
=========================================*/

$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - WAVE</title>
    <link rel="shortcut icon" href="img_iconos/servicios_2.png" type="image/x-icon">
    <link rel="stylesheet" href="css/servicios_1.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->
    <script defer src="js/servicios.js"></script>
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

                <h1>Servicios - WAVE</h1>

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

        <a href="servicios_crear.php" class="btn-new">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </a>

    </div>

    <div class="pizarra">

        <h2 class="titulo-pizzarra">
            🟢 Datos
        </h2>
        

            <div class="usuarios-cards">

                <?php if (!empty($servicios)): ?>

                    <?php foreach ($servicios as $row): ?>
                        
                        <?php $id = (int)$row['id_servicio']; ?>

                        <div class="usuario-card">

                           
                            <!-- Imagen -->
                            <div class="usuario-img">
                                <?php if (!empty($row['imagen'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($row['imagen']) ?>" 
                                        alt="Usuario"
                                    >
                                <?php else: ?>
                                    <img src="img/user.png" alt="Usuario">
                                <?php endif; ?>
                            </div>

                            <!-- Datos -->
                            <div class="usuario-info">
                                <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                                <p><strong>Descripción:</strong> <?= htmlspecialchars($row['descripcion']) ?></p>
                                
                                

                            </div>

                            <!-- Acciones -->
                            <div class="usuario-actions">

                                <a href="servicios_editar.php?id=<?= $id ?>" class="btn-edit">
                                    <i class="fas fa-pen"></i>
                                    <span>Editar</span>
                                </a>

                             

                                <a href="servicios_borrar.php?id=<?= $id ?>"
                                class="btn-delete"
                                onclick="return confirm('¿ 🗑️ Eliminar Servicio: <?= htmlspecialchars($row['nombre']) ?>?')">

                                    <i class="fas fa-trash"></i>
                                    <span>Borrar</span>

                                </a>
                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No existen Servicios registrados.</p>

                <?php endif; ?>

            </div>
    </div>












   
</body>
</html>