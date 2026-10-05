<?php
include("auth.php");      // Protege la página
include("conexion.php");  // Conexión BD


/*=========================================
= DATOS DE SESIÓN
=========================================*/
$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1       = $_SESSION['name'];
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
= CONSULTAR USUARIOS
=========================================*/
$sql = "SELECT a.id_usuario, a.nombre, a.password, a.name, a.imagen, a.estado
        FROM usuario a
        ORDER BY a.id_usuario DESC";

$result = $conn->query($sql);

/*=========================================
= GUARDAR DATOS EN ARRAY
=========================================*/
$usuarios = [];

if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $usuarios[] = $fila;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - WAVE</title>
    <link rel="shortcut icon" href="img/usuarios_1.png" type="image/x-icon">
    <link rel="stylesheet" href="css/usuarios_2.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <script defer src="js/usuarios.js"></script>
</head>





<style>
    #toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    color: white;
    font-weight: 500;
    z-index: 9999;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    }

    #toast.success {
        background-color: #2ecc71;
    }

    #toast.error {
        background-color: #e74c3c;
    }
</style>



<body>


    <!-- Toast -->
    <div id="toast" class="toast"></div>

    <script>
    window.addEventListener('DOMContentLoaded', () => {
        const toastDiv = document.getElementById('toast');

         // Usar la variable $mensaje que ya definimos en PHP
        const mensaje = <?php echo $mensaje ? json_encode($mensaje) : 'null'; ?>;

        if (mensaje) {
            toastDiv.classList.add(mensaje.tipo); // success o error
            toastDiv.textContent = mensaje.texto;
            //toastDiv.innerHTML = mensaje.texto;
            toastDiv.style.opacity = 1;
            toastDiv.style.transform = 'translateY(0)';

            setTimeout(() => {
                toastDiv.style.opacity = 0;
                toastDiv.style.transform = 'translateY(-20px)';
            }, 4000);
        }
    });
    </script>


    <header>
        <div class="top-line"></div> <!-- Línea semitransparente superior -->

        <div class="header-container">

                <div class="logo">
                    <a href="dashboard.php">
                        <img src="img/main.png" alt="Logo">
                    </a>
                </div>

                <h1>Usuarios - NEXUS</h1>

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
    

    <div class="acciones">

        <a href="dashboard.php" class="btn-back">
            <i class="fas fa-reply"></i>
            <span>Regresar</span>
        </a>

        <a href="usuarios_crear.php" class="btn-new">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </a>

    </div>


    <!-- CARDS -->
        <div class="grid-container-cards">

            <h2 class="estado-title title-proceso">
                🟢 Usuarios
            </h2>

            <div class="usuarios-cards">

                <?php if (!empty($usuarios)): ?>

                    <?php foreach ($usuarios as $row): ?>
                        
                        <?php $id = (int)$row['id_usuario']; ?>

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
                                <h3><?= htmlspecialchars($row['name']) ?></h3>
                                <p><strong>Usuario:</strong> <?= htmlspecialchars($row['nombre']) ?></p>
                                
                                <p>
                                    <strong>Estado:</strong>

                                    <?php if ($row['estado'] == 1): ?>

                                        <span class="badge-activo">
                                            Activo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge-inactivo">
                                            Inactivo
                                        </span>

                                    <?php endif; ?>

                                </p>

                            </div>

                            <!-- Acciones -->
                            <div class="usuario-actions">

                                <a href="usuarios_editar.php?id=<?= $id ?>" class="btn-edit">
                                    <i class="fas fa-pen"></i>
                                    <span>Editar</span>
                                </a>

                                <!--<a href="usuarios_borrar.php?id=<?= $id ?>"
                                class="btn-delete"
                                onclick="return confirm('¿Eliminar el usuario: <?= htmlspecialchars($row['nombre']) ?>?')">

                                    <i class="fas fa-trash"></i>
                                    <span>Borrar</span>

                                </a>-->

                                <?php if ($id != $id_usuario): ?>

                                    <a href="usuarios_borrar.php?id=<?= $id ?>"
                                    class="btn-delete"
                                    onclick="return confirm('¿Eliminar el usuario: <?= htmlspecialchars($row['nombre']) ?>?')">

                                        <i class="fas fa-trash"></i>
                                        <span>Borrar</span>

                                    </a>

                                <?php else: ?>

                                    <span class="btn-session">
                                        <i class="fas fa-user-check"></i>
                                        Sesión actual
                                    </span>

                                <?php endif; ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No existen usuarios registrados.</p>

                <?php endif; ?>

            </div>

        </div>

   
    
</body>
</html>