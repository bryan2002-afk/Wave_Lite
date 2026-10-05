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
    <link rel="stylesheet" href="css/categorias.css">


    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->  

    <script defer src="js/categorias.js"></script>
</head>


<style>

    .tabla-responsive{
        width:100%;
        overflow-x:auto;
    }

    .tabla-categorias{
        width:100%;
        border-collapse:collapse;
        background:#252525;
        color:#fff;
        border-radius:12px;
        overflow:hidden;
        min-width:700px;
    }

    .tabla-categorias thead{
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #fff;
    }

    .tabla-categorias thead th{
        padding:16px;
        text-align:left;
        font-size:16px;
        color:#fff;
        letter-spacing:.5px;
        text-transform:uppercase;
        border-bottom:2px solid rgba(255,255,255,.15);
        transition:.3s;
    }

    .tabla-categorias thead th:hover{
        background:rgba(255,255,255,.08);
    }

    .tabla-categorias tbody td{
        padding:15px;
        border-bottom:1px solid #3b3b3b;
        vertical-align:middle;
    }

    .tabla-categorias tbody tr:hover{
        background:#323232;
    }

    .acciones{
        display:flex;
        gap:10px;
        justify-content:center;
    }

    .btn-edit,
    .btn-delete{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:8px 14px;
        border-radius:8px;
        text-decoration:none;
        color:#fff;
        font-size:14px;
        transition:.25s;
    }

    .btn-edit{
        background:#2196f3;
    }

    .btn-edit:hover{
        background:#1976d2;
    }

    .btn-delete{
        background:#e53935;
    }

    .btn-delete:hover{
        background:#c62828;
    }

    @media (max-width:768px){

        .tabla-categorias{
            min-width:600px;
        }

        .tabla-categorias thead th,
        .tabla-categorias tbody td{
            padding:12px;
            font-size:14px;
        }

        .btn-edit,
        .btn-delete{
            padding:7px 10px;
            font-size:13px;
        }
    }
</style>


<!--
<style>
    /*==============================
        CONTENEDOR RESPONSIVO
    ==============================*/
    .tabla-responsive{
        width:100%;
        overflow-x:auto;
        overflow-y:hidden;
        -webkit-overflow-scrolling:touch;
        border-radius:12px;
    }

    /*==============================
        TABLA
    ==============================*/
    .tabla-categorias{
        width:100%;
        min-width:700px;
        border-collapse:collapse;
        background:#252525;
        color:#fff;
        border-radius:12px;
        overflow:hidden;
        white-space:nowrap;
    }

    .tabla-categorias thead{
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
    }

    .tabla-categorias thead th{
        padding:16px;
        text-align:left;
        font-size:16px;
        color:#fff;
        letter-spacing:.5px;
        text-transform:uppercase;
        border-bottom:2px solid rgba(255,255,255,.15);
        transition:.3s;
    }

    .tabla-categorias thead th:hover{
        background:rgba(255,255,255,.08);
    }

    .tabla-categorias tbody td{
        padding:15px;
        border-bottom:1px solid #3b3b3b;
        vertical-align:middle;
    }

    .tabla-categorias tbody tr{
        transition:.25s;
    }

    .tabla-categorias tbody tr:hover{
        background:#323232;
    }

    /*==============================
        BOTONES
    ==============================*/
    .acciones{
        display:flex;
        justify-content:center;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .btn-edit,
    .btn-delete{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        padding:8px 14px;
        border-radius:8px;
        text-decoration:none;
        color:#fff;
        font-size:14px;
        font-weight:500;
        transition:.25s;
        white-space:nowrap;
    }

    .btn-edit{
        background:#2196f3;
    }

    .btn-edit:hover{
        background:#1976d2;
    }

    .btn-delete{
        background:#e53935;
    }

    .btn-delete:hover{
        background:#c62828;
    }

    /*==============================
        TABLETS
    ==============================*/
    @media (max-width:992px){

        .tabla-categorias{
            min-width:650px;
        }

        .tabla-categorias thead th,
        .tabla-categorias tbody td{
            padding:13px;
            font-size:14px;
        }

    }

    /*==============================
        MOTO G34 5G Y MÓVILES
    ==============================*/
    @media (max-width:768px){

        .tabla-responsive{
            border-radius:10px;
        }

        .tabla-categorias{
            min-width:560px;
        }

        .tabla-categorias thead th,
        .tabla-categorias tbody td{
            padding:10px;
            font-size:13px;
        }

        .acciones{
            gap:6px;
        }

        .btn-edit,
        .btn-delete{
            padding:7px 10px;
            font-size:12px;
            border-radius:6px;
        }

    }

    /*==============================
        TELÉFONOS PEQUEÑOS
    ==============================*/
    @media (max-width:480px){

        .tabla-categorias{
            min-width:500px;
        }

        .tabla-categorias thead th,
        .tabla-categorias tbody td{
            padding:8px;
            font-size:12px;
        }

        .btn-edit,
        .btn-delete{
            padding:6px 8px;
            font-size:11px;
            gap:4px;
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
        <br>

            <div class="tabla-responsive">

                <?php if (!empty($categorias)): ?>

                    <table class="tabla-categorias">

                        <thead>
                            <tr>
                                <th style="width:80px;">ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th style="width:180px;">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($categorias as $row): ?>

                                <?php $id = (int)$row['id_categoria']; ?>

                                <tr>

                                    <td><?= $id ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nombre']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </td>

                                    <td class="acciones">

                                        <a href="categorias_editar.php?id=<?= $id ?>" class="btn-edit">
                                            <i class="fas fa-pen"></i>
                                            Editar
                                        </a>

                                        <a href="categorias_borrar.php?id=<?= $id ?>"
                                        class="btn-delete"
                                        onclick="return confirm('¿🗑️ Eliminar: <?= htmlspecialchars($row['nombre']) ?>?')">

                                            <i class="fas fa-trash"></i>
                                            Borrar

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php else: ?>

                    <p>No existen categorías registradas.</p>

                <?php endif; ?>

            </div>

    </div>












   
</body>
</html>