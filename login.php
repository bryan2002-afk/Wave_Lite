<?php

/*=========================================
= CONFIGURACIÓN SEGURA DE SESIÓN
=========================================*/

session_set_cookie_params([
    'httponly' => true,
    'secure'   => isset($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);

session_start();


/*=========================================
= SI YA HAY SESIÓN
=========================================*/

if (isset($_SESSION['id_usuario'])) {

    header("Location: dashboard.php");
    exit();

}


/*=========================================
= CONEXIÓN SQLITE
=========================================*/

include("conexion.php");


$error_message = "";


/*=========================================
= LOGIN
=========================================*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $nombre   = trim($_POST['nombre'] ?? '');
    $password = trim($_POST['password'] ?? '');



    if (empty($nombre) || empty($password)) {


        $error_message = "Por favor complete todos los campos.";


    } else {



        $query = "
            SELECT 
                id_usuario,
                nombre,
                name,
                imagen,
                password
            FROM usuario
            WHERE nombre = ?
            LIMIT 1
        ";



        $stmt = $conn->prepare($query);



        if ($stmt) {


            $stmt->execute([$nombre]);


            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);



            if ($usuario) {



                $loginCorrecto = false;



                /*=========================================
                = VERIFICAR HASH
                =========================================*/

                if (password_verify($password, $usuario['password'])) {


                    $loginCorrecto = true;


                }



                /*=========================================
                = COMPATIBILIDAD TEXTO PLANO
                =========================================*/

                elseif ($password === $usuario['password']) {



                    $loginCorrecto = true;



                    // Convertir contraseña antigua a HASH

                    $nuevoHash = password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );



                    $update = "
                        UPDATE usuario
                        SET password = ?
                        WHERE id_usuario = ?
                    ";



                    $stmtUpdate = $conn->prepare($update);



                    $stmtUpdate->execute([
                        $nuevoHash,
                        $usuario['id_usuario']
                    ]);



                }





                /*=========================================
                = LOGIN CORRECTO
                =========================================*/


                if ($loginCorrecto) {



                    /*=========================================
                    = ACTUALIZAR ESTADO ACTIVO
                    =========================================*/


                    $sqlEstado = "
                        UPDATE usuario
                        SET estado = 1
                        WHERE id_usuario = ?
                    ";



                    $stmtEstado = $conn->prepare($sqlEstado);



                    $stmtEstado->execute([
                        $usuario['id_usuario']
                    ]);




                    /*=========================================
                    = REGENERAR SESIÓN
                    =========================================*/

                    session_regenerate_id(true);



                    /*=========================================
                    = VARIABLES DE SESIÓN
                    =========================================*/

                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nombre']     = $usuario['nombre'];
                    $_SESSION['name']       = $usuario['name'];
                    $_SESSION['imagen']     = $usuario['imagen'];
                    $_SESSION['bienvenida'] = true;



                    header("Location: bienvenida.php");
                    exit();



                } else {



                    $error_message = "Usuario o contraseña incorrectos.";


                }



            } else {



                $error_message = "Usuario o contraseña incorrectos.";


            }



        } else {


            $error_message = "Error al preparar la consulta.";


        }


    }


}

?>



<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - WAVE</title>
<link rel="shortcut icon" href="img/secu.png" type="image/x-icon">
<link rel="stylesheet" href="css/login.css">

<link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">


<script defer src="js/login.js"></script>

</head>




<style>
        /*=========================================
    = BOTÓN REGRESAR
    =========================================*/
    .btn-back{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:10px;

        width:50%;
        margin-top:18px;
        padding:14px 18px;

        background:linear-gradient(135deg,#b84d9b,#d96bb8);

        color:#fff;
        text-decoration:none;

        border-radius:14px;

        font-size:15px;
        font-weight:600;

        box-shadow:
            0 10px 25px rgba(184,77,155,.28);

        transition:all .30s ease;
    }

    .btn-back i{
        font-size:17px;
        transition:.30s ease;
    }

    .btn-back:hover{
        transform:translateY(-3px);

        background:linear-gradient(135deg,#a63f8b,#c85ca8);

        box-shadow:
            0 15px 30px rgba(184,77,155,.40);
    }

    .btn-back:hover i{
        transform:translateX(-5px);
    }

    .btn-back:active{
        transform:scale(.98);
    }

    .btn-back:focus{
        outline:none;

        box-shadow:
            0 0 0 4px rgba(184,77,155,.18),
            0 10px 25px rgba(184,77,155,.28);
    }




    /*=========================================
    = ICONO MOSTRAR CONTRASEÑA
    =========================================*/
    .toggle-password{
        position:absolute;
        right:20px;
        top:50%;
        transform:translateY(-50%);

        color:rgba(255,255,255,.75);
        font-size:20px;

        cursor:pointer;

        display:none;

        transition:
            color .25s ease,
            transform .25s ease,
            opacity .25s ease;
    }

    .toggle-password:hover{
        color:#ffffff;
        transform:translateY(-50%) scale(1.15);
    }

    .toggle-password:active{
        transform:translateY(-50%) scale(.95);
    }

    .toggle-password.fa-eye-slash{
        color:#ffd6f5;
    }
</style>

<body>

    <div class="login-container">

        <div class="header-wave">
            <h1>Iniciar Sesión</h1>
        </div>

        <form id="loginForm" class="form-container" method="POST">

            <?php if(!empty($error_message)): ?>
                <div class="error">
                    <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>


            <!-- Grupo de Usuario -->
            <div class="input-group">
                <i class="fa-solid fa-user"></i> <!-- Reemplazado SVG por icono de usuario -->
                <input type="text" name="nombre" placeholder="Usuario" required>
            </div>

            <!-- Grupo de Contraseña -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i> <!-- Reemplazado SVG por icono de candado -->
                <input type="password" name="password" placeholder="Contraseña" id="password" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <!-- Botón de enviar -->
            <button type="submit" class="submit-btn">
                <i class="fa-solid fa-chevron-right"></i> <!-- Reemplazado SVG por flecha derecha -->
            </button>

            
            <a href="index.html" class="btn-back">
                <i class="fa-solid fa-circle-left"></i>
                <span>Regresar</span>
            </a>

        </form>

        <div class="decorations">
            <div class="earbud-left"></div>
            <div class="earbud-right"></div>
            <div class="music-note note-1">&#9835;</div>
            <div class="music-note note-2">&#9834;</div>
        </div>

    </div>

    <audio id="clickSound" preload="auto">
        <source src="audio/he_hee.mp3" type="audio/mpeg">
    </audio>



    <script>
        
        // Obtener los elementos del formulario
        const password = document.getElementById("password");
        const toggle = document.getElementById("togglePassword");

        // Detectar cuando se escribe algo en el campo de contraseña
        password.addEventListener("input", function() {
            if (password.value.trim() !== "") {
                // Si hay texto en el campo, mostrar el icono del ojo
                toggle.style.display = "block";
            } else {
                // Si no hay texto, ocultar el icono
                toggle.style.display = "none";
            }
        });

        // Mostrar u ocultar la contraseña al hacer clic en el icono del ojo
        toggle.addEventListener("click", function() {
            if (password.type === "password") {
                // Mostrar la contraseña (cambiar tipo de input)
                password.type = "text";
                toggle.classList.remove("fa-eye");
                toggle.classList.add("fa-eye-slash");
            } else {
                // Ocultar la contraseña (volver al tipo password)
                password.type = "password";
                toggle.classList.remove("fa-eye-slash");
                toggle.classList.add("fa-eye");
            }
        });

    </script>
</body>
</html>

