<?php

session_start();


/*=========================================
= CONEXIÓN SQLITE
=========================================*/

include("conexion.php");



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

    <style>
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
</head>
<body>

    <div class="box">
        <h1>Good Bye 👋</h1>
        <p>Cerrando sesión</p>

        <div class="dots-loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

    </div>

</body>
</html>