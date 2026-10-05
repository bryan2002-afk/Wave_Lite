<?php

include("auth.php");
include("conexion.php");


$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'];
$name_1     = $_SESSION['name'];
$imagen     = $_SESSION['imagen'] ?? 'img/perfil.png';



/*=========================================
= MENSAJE
=========================================*/

$mensaje = null;

if(isset($_SESSION['mensaje'])){

    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);

}



/*=========================================
= OBTENER SERVICIOS
=========================================*/

function obtenerLista($conn,$sql){

    $data=[];

    $stmt=$conn->query($sql);

    if($stmt){

        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    return $data;

}



$servicios = obtenerLista(
    $conn,
    "SELECT id_servicio,nombre
     FROM servicio
     ORDER BY nombre ASC"
);



/*=========================================
= OBTENER ID
=========================================*/

if(!isset($_GET['id'])){

    header("Location: cuentas.php");
    exit();

}


$id_cuenta=(int)$_GET['id'];



/*=========================================
= OBTENER CUENTA
=========================================*/


$sql="
SELECT *
FROM cuenta
WHERE id_cuenta = ?
";


$stmt=$conn->prepare($sql);

$stmt->execute([
    $id_cuenta
]);


$cuenta=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$cuenta){


    $_SESSION['mensaje']=[

        "tipo"=>"error",
        "texto"=>"La cuenta no existe."

    ];


    header("Location: cuentas.php");
    exit();

}





/*=========================================
= ACTUALIZAR
=========================================*/

if(isset($_POST['guardar'])){


    $correo =
        strtolower(
            trim($_POST['correo'])
        );


    $password =
        trim($_POST['password']);


    $id_servicio =
        (int)$_POST['id_servicio'];


    $notas =
        trim($_POST['notas']);



    /* VALIDACIONES */


    if(empty($correo) || empty($id_servicio)){


        $_SESSION['mensaje']=[

            "tipo"=>"error",
            "texto"=>"Servicio y correo son obligatorios."

        ];


        header(
            "Location: cuentas_editar.php?id=".$id_cuenta
        );

        exit();

    }




    if(!filter_var($correo,FILTER_VALIDATE_EMAIL)){


        $_SESSION['mensaje']=[

            "tipo"=>"error",
            "texto"=>"Correo inválido."

        ];


        header(
            "Location: cuentas_editar.php?id=".$id_cuenta
        );

        exit();

    }





    /*=========================================
    = VERIFICAR DUPLICADOS
    =========================================*/


    $sql_check="

        SELECT id_cuenta
        FROM cuenta
        WHERE correo = ?
        AND id_servicio = ?
        AND id_cuenta <> ?

    ";


    $stmt_check=$conn->prepare($sql_check);


    $stmt_check->execute([

        $correo,
        $id_servicio,
        $id_cuenta

    ]);



    if($stmt_check->fetch()){


        $_SESSION['mensaje']=[

            "tipo"=>"error",
            "texto"=>"Ese correo ya está registrado para este servicio."

        ];


        header(
            "Location: cuentas_editar.php?id=".$id_cuenta
        );

        exit();

    }






    /*=========================================
    = CONSERVAR PASSWORD
    =========================================*/


    if(empty($password)){


        $password=$cuenta['password'];

    }







    /*=========================================
    = UPDATE SQLITE
    =========================================*/


    $sql="

    UPDATE cuenta SET

        correo = ?,
        password = ?,
        id_servicio = ?,
        notas = ?

    WHERE id_cuenta = ?

    ";



    $stmt=$conn->prepare($sql);



    if(
        $stmt->execute([

            $correo,
            $password,
            $id_servicio,
            $notas,
            $id_cuenta

        ])
    ){


        $_SESSION['mensaje']=[

            "tipo"=>"success",
            "texto"=>"☑️ Cuenta actualizada correctamente."

        ];



        header("Location: cuentas.php");
        exit();



    }else{


        $_SESSION['mensaje']=[

            "tipo"=>"error",
            "texto"=>"❌ Error al actualizar la cuenta."

        ];


    }



}



?>



<!DOCTYPE html>
<html lang="es">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Cuentas - WAVE</title>

    <link rel="shortcut icon" href="img2/cuentas-2.png" type="image/x-icon">

    <link rel="stylesheet" href="css/cuentas_editar.css">

    <link href='https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css' rel='stylesheet' />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Lilita+One&family=Righteous&display=swap" rel="stylesheet">



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Script de Mensaje Flotante -->

    <script defer src="js/cuentas_crear.js"></script>


</head>



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
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:Arial, Helvetica, sans-serif;
    }

    body{
        background: #181818;
        color:#fff;
    }

    /*======================
            HEADER
    ======================*/

    /*
    font-family: "Lilita One", cursive;
    font-family: "Inter", sans-serif;
    font-family: "Righteous", sans-serif;
    */

    header{
        position:sticky;
        top:0;
        z-index:1000;

        display:flex;
        justify-content:space-between;
        align-items:center;

        min-height:72px;
        padding:12px 24px;

        background: #232323; /* #232323 */
        border-bottom:1px solid #303030;
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

    .header-left{
        display:flex;
        align-items:center;
        gap:14px;
        min-width:0;
    }

    .logo-link{
        display:flex;
        align-items:center;
        text-decoration:none;
    }

    .logo{
        width:clamp(34px,4vw,42px);
        height:clamp(34px,4vw,42px);
        object-fit:contain;
        cursor:pointer;
        transition:.3s;
    }

    .logo:hover{
        transform:scale(1.08);
    }

    .titulo{
        display:flex;
        flex-direction:column;
        justify-content:center;
        min-width:0;
    }

    .titulo p{
        margin:0;
        color:#b5b5b5;
        font-family:"Inter", sans-serif;
        font-size:clamp(12px,1.8vw,15px);
    }

    .titulo h3{
        
        margin:0;
        line-height:1.1;
        font-family:"Righteous", sans-serif;
        font-size:clamp(20px,3vw,28px);
    }

    .icono{
        font-size:28px;
        cursor:pointer;
    }

    /*======================
        PERFIL DE USUARIO
    ======================*/

    .profile-container{
        position:relative;
        flex-shrink:0;
    }

    .profile-btn{
        display:flex;
        align-items:center;
        gap:12px;

        min-width:185px;
        max-width:230px;

        padding:8px 12px;

        background:#2b2b2b;
    /* border:1px solid #3c3c3c;
        border:1px solid #f425ca;*/
        border:1px solid #f425ca;
        border-radius:12px;

        color:#fff;
        cursor:pointer;

        transition:.25s;
    }

    .profile-btn:hover{
        /*
        background:#353535;
        border-color:#555;
        */

    
    transform:translateY(-2px);

        box-shadow:
            0 15px 30px rgba(0,0,0,.10);
        
    }

    .profile-btn img{
        width:42px;
        height:42px;
        border-radius:50%;
        object-fit:cover;
    /* border:2px solid #4d4d4d;*/
        border:2px solid #f425ca;
    }

    .profile-name{
        flex:1;
        font-size:15px;
        font-weight:600;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .arrow{
        margin-left:auto;
        font-size:28px;
        transition:transform .3s;
        color: #f425ca;
    }

    .arrow.rotate{
        transform:rotate(180deg);
    }

    /*======================
        MENÚ DESPLEGABLE
    ======================*/

    .dropdown-menu{
        position:absolute;
        top:calc(100% + 10px);
        right:0;

        width:210px;

        background:#2b2b2b;
        border:1px solid #3c3c3c;
        border-radius:12px;

        list-style:none;
        overflow:hidden;

        display:none;

        box-shadow:0 10px 25px rgba(0,0,0,.35);

        z-index:1000;
    }

    .dropdown-menu.active{
        display:block;
    }

    .dropdown-menu li{
        border-bottom:1px solid #3c3c3c;
    }

    .dropdown-menu li:last-child{
        border-bottom:none;
    }

    .dropdown-menu li a{
        display:flex;
        align-items:center;
        gap:12px;

        padding:13px 15px;

        text-decoration:none;
        color:#fff;
        font-size:15px;

        transition:.25s;
    }

    .dropdown-menu li a:hover{
        background:#3a3a3a;
    }

    .dropdown-menu li a i{
        font-size:20px;
        color:#9ec5ff;
    }

    .dropdown-user{
        display:none;
        align-items:center;
        gap:12px;
        padding:15px;
        border-bottom:1px solid #3c3c3c;
    }

    .dropdown-user img{
        width:42px;
        height:42px;
        border-radius:50%;
        object-fit:cover;
    }

    .dropdown-user span{
        font-size:16px;
        font-weight:600;
    }



    /*======================
            GRID
    ======================*/

    .grid{
        padding:25px;
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(170px,1fr));
        gap:30px;
    }

    /*======================
            CARD
    ======================*/

    .card{
        text-decoration:none;
        color:#fff;
        text-align:center;
    }

    .imagen{
        width:120px;
        height:120px;

        margin:auto;

        border:1px solid #666;
        border-radius:22px;

        display:flex;
        justify-content:center;
        align-items:center;

        transition:.3s;
    }

    .imagen img{
        width:90px;
        height:90px;
        object-fit:contain;
    }

    .card:hover .imagen{
        background:#2e2e2e;
        transform:translateY(-5px);
    }

    .nombre{
        margin-top:15px;
        font-size:20px;
        font-family:"Righteous", sans-serif;
    }

    .info{
        margin-top:5px;
        color:#b5b5b5;
        font-size:18px;
    }

    /*======================
        TABLETS
    ======================*/

    @media (max-width:900px){

        header{
            padding:10px 16px;
        }

        .profile-btn{
            min-width:170px;
        }

        .grid{
            grid-template-columns:repeat(3,1fr);
            gap:22px;
        }

    }

    /*======================
        TELÉFONOS
    ======================*/

    @media (max-width:600px){

        header{
            padding:10px 12px;
            gap:10px;
        }

        .header-left{
            gap:10px;
        }

        .logo{
            width:36px;
            height:36px;
        }

        .titulo h3{
            font-size:20px;
        }

        .titulo p{
            font-size:12px;
        }

        /*======================
            PERFIL
        ======================*/

        .profile-btn{
            min-width:auto;
            width:auto;
            padding:6px 10px;
            gap:8px;
        }

        .profile-btn img{
            width:36px;
            height:36px;
        }

        /* Oculta el nombre del botón */
        .profile-name{
            display:none;
        }

        .arrow{
            margin-left:0;
            font-size:24px;
        }

        .dropdown-menu{
            width:190px;
        }

        /* Muestra el encabezado del menú */
        .dropdown-user{
            display:flex;
            align-items:center;
            gap:12px;
            padding:15px;
            background:#313131;
            border-bottom:1px solid #454545;
        }

        .dropdown-user img{
            width:42px;
            height:42px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid #4d4d4d;
        }

        .dropdown-user span{
            font-size:16px;
            font-weight:600;
            color:#fff;
        }

        /*======================
            GRID
        ======================*/

        .grid{
            grid-template-columns:repeat(3,1fr);
            gap:18px;
            padding:18px;
        }

        .imagen{
            width:95px;
            height:95px;
        }

        .imagen img{
            width:70px;
            height:70px;
        }

        .nombre{
            font-size:18px;
        }

        .info{
            font-size:15px;
        }

    }



    /*======================
    TELÉFONOS PEQUEÑOS
    ======================*/

    @media (max-width:420px){

        header{
            padding:8px 10px;
        }

        .titulo h3{
            font-size:18px;
        }

        .titulo p{
            font-size:11px;
        }

        .logo{
            width:34px;
            height:34px;
        }

        .profile-btn{
            padding:6px 8px;
        }

        .profile-btn img{
            width:34px;
            height:34px;
        }

        .dropdown-menu{
            width:180px;
        }

        .grid{
            gap:16px;
            padding:16px;
        }

        .imagen{
            width:85px;
            height:85px;
        }

        .imagen img{
            width:62px;
            height:62px;
        }

        .nombre{
            font-size:16px;
        }

        .info{
            font-size:14px;
        }

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

    <header>
        <div class="top-line"></div>

        <div class="header-left">
            <a href="dashboard.php" class="logo-link">
                <img src="img2/maiin.png" class="logo" alt="WAVE">
            </a>

            <div class="titulo">
                <p>Wave</p>
                <h3>Editar/Cuentas</h3>
            </div>
        </div>


  

        <div class="profile-container">

            <button class="profile-btn" type="button" aria-expanded="false" aria-label="Menú de usuario">
                <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Foto de perfil de Bryan">
                <span class="profile-name"><?php echo htmlspecialchars($nombre); ?></span>
                <i class="ri-arrow-drop-down-line arrow" aria-hidden="true"></i>
            </button>

            <ul class="dropdown-menu">

                <li class="dropdown-user">
                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Foto de perfil">
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($nombre); ?></span>
                        <small><?php echo htmlspecialchars($name_1); ?></small>
                    </div>
                </li>

                <li>
                    <a href="perfil.php?id=<?= $id_usuario ?>">
                        <i class="ri-user-line"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>

                <li>
                    <a href="configuracion.php?id=<?= $id_usuario ?>">
                        <i class="ri-settings-3-line"></i>
                        <span>Configuración</span>
                    </a>
                </li>

                <li>
                    <a href="logout.php">
                        <i class="ri-logout-box-r-line"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </li>
            </ul>

        </div>



    </header>

 

    <div class="pizarra">

        <h2 class="titulo-pizzarra">
                🟢 Edite los datos
            </h2>

            <form action="" method="POST" enctype="multipart/form-data" class="form-producto">


                            <div class="form-buttons" style="justify-content: center;">

                                <button type="submit" name="guardar" class="btn-save" >
                                    <i class="fas fa-arrows-rotate"></i> Actualizar 
                                </button>
                                <a href="cuentas_ver.php?id_servicio=<?= (int)$cuenta['id_servicio'] ?>" class="btn-cancel" >
                                    <i class="fas fa-times"></i> Cancelar
                                </a>

                            </div>

                            <div class="form-group full">
                                <label>Servicio:</label>
                                <select name="id_servicio" required>
                                    <option value="">-- Seleccione Servicio --</option>

                                    <?php foreach($servicios as $cat): ?>
                                        <option value="<?= $cat['id_servicio']; ?>"
                                            <?= ($cat['id_servicio'] == $cuenta['id_servicio']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Correo:</label>
                                <input type="email" name="correo" value="<?= htmlspecialchars($cuenta['correo']) ?>" required>
                            </div>    

                            <div class="form-group">
                                <label>Contraseña:</label>
                                <input
                                    type="password" name="password" id="password"
                                    placeholder="Dejar vacío para conservar contraseña">
                            </div>

                            <div class="form-group full">
                                <label>Notas:</label>
                                <textarea
                                    name="notas"
                                    rows="4"
                                    placeholder="Escriba una nota..."><?php echo htmlspecialchars($cuenta['notas']); ?></textarea>
                            </div>
                          
                      
                           

                        </form>

    </div>

   

</body>
</html>

