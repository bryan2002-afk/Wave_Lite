<?php

session_start();


/*=========================================
= EVITAR CACHE
=========================================*/

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");



/*=========================================
= VALIDAR SESIÓN
=========================================*/

if (!isset($_SESSION['id_usuario'])) {

    header("Location: login.php");
    exit();

}



/*=========================================
= DATOS DEL USUARIO
=========================================*/

$nombre = $_SESSION['name'] ?? 'Usuario';



/*=========================================
= REDIRECCIÓN AL DASHBOARD
=========================================*/

header("Refresh: 3; url=dashboard.php");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido...</title>
    <link rel="shortcut icon" href="img/god.png" type="image/x-icon">
<!--
    <style>
        body{
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#0d6efd;
            color:white;
            font-family:Arial, sans-serif;
        }

        .box{
            text-align:center;
        }

        h1{
            font-size:45px;
            margin-bottom:15px;
        }

        p{
            font-size:18px;
            opacity:.9;
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
-->
    
</head>

<style>
        body{
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#0d6efd;
            color:white;
            font-family:Arial, sans-serif;
        }

        .box{
            text-align:center;
        }

        h1{
            font-size:45px;
            margin-bottom:15px;
        }

        p{
            font-size:18px;
            opacity:.9;
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

    <div class="box">
        <h1>Bienvenido a WAVE 🚀</h1>
        <p>Iniciando sesión</p>
    
    
        <div class="dots-loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

    </div>

    <!--<div class="dots-loader">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>-->

</body>
</html>