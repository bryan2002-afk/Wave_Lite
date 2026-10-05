<?php

session_start();


/*=========================================
= CONEXIÓN SQLITE
=========================================*/

include("conexion.php");



/*=========================================
= MENSAJE
=========================================*/

$mensaje = null;


if (isset($_SESSION['mensaje'])) {

    $mensaje = $_SESSION['mensaje'];

    unset($_SESSION['mensaje']);

}



/*=========================================
= EVITAR CACHE
=========================================*/

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");



/*=========================================
= CAMBIAR ESTADO A INACTIVO
=========================================*/

if (isset($_SESSION['id_usuario'])) {


    $id_usuario = (int) $_SESSION['id_usuario'];



    $sql = "
        UPDATE usuario
        SET estado = 0
        WHERE id_usuario = ?
    ";



    $stmt = $conn->prepare($sql);



    if ($stmt) {


        $stmt->execute([
            $id_usuario
        ]);


    }


}



/*=========================================
= LIMPIAR SESIÓN
=========================================*/

$_SESSION = [];



/*=========================================
= ELIMINAR COOKIE DE SESIÓN
=========================================*/

if (ini_get("session.use_cookies")) {


    $params = session_get_cookie_params();



    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );


}



/*=========================================
= DESTRUIR SESIÓN
=========================================*/

session_destroy();



/*=========================================
= REDIRECCIÓN AUTOMÁTICA
=========================================*/

header("Refresh: 3; url=login.php");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrando Session...</title>
    <link rel="shortcut icon" href="img/close.png" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante --> 

</head>

<style>

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


        body{
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#111;
            color:white;
            font-family:Arial, sans-serif;
        }

        .box{
            text-align:center;
        }

        h1{
            font-size:50px;
            margin-bottom:10px;
        }

        p{
            font-size:18px;
            opacity:.8;
        }



    .dots-loader {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 5px;
      margin-top: 100px;
    }

    .dot {
      width: 12px;
      height: 12px;
      background-color: white; /* #3498db */
      border-radius: 50%;
      animation: bounce 1.2s infinite ease-in-out;
    }

    .dot:nth-child(2) {
      animation-delay: 0.2s;
    }

    .dot:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes bounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
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

    <div class="box">
        <h1>Restarting 🔄​</h1>
        <p>Reiniciar sesión</p>

        <div class="dots-loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

    </div>

</body>
</html>