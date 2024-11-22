<?php

include("config.php");

function enviarCorreoBienvenida($correo) {
    $asunto = "Bienvenido a SpiderThreads";
    $mensaje = "¡Hola!\n\nBienvenido a SpiderThreads. Gracias por registrarte.\n\nSaludos,\nEl equipo de SpiderThreads";
    $cabeceras = "From: williamscesar21@gmail.com\r\n";
    $cabeceras .= "Reply-To: williamscesar21@gmail.com\r\n";
    mail($correo, $asunto, $mensaje, $cabeceras);
}

$mensaje = null;

session_start();
if (!empty($_SESSION["id"])){
    header("Location: dashboard.php");
    exit(); 
}

if(isset($_POST["submit"])){
    
    $nombreUsuario = $_POST["nombre_usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $rol = "usuario"; 

    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo_electronico = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result(); 
    if($stmt->num_rows > 0){
        echo "El correo electrónico ya está registrado. Por favor, intente con otro.";
    } else {
        
        $contrasenaEncriptada = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombreUsuario, $correo, $contrasenaEncriptada, $rol);
        if($stmt->execute()){
            enviarCorreoBienvenida($correo); 
            header("Location: dashboard.php");
        } else {
            
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css1.css">
    <title>Registro - SpiderThreads</title>
</head>
<body>
    <div id="titulo">
        <h1>Registro - SpiderThreads</h1>
        <img src="spider.png" id="spider" alt="">
    </div>
    
    <form action="" id="rectangulo1" method="post">
        <label for="nombre_usuario">Nombre</label>
        <input name="nombre_usuario" id="nombre_usuario" type="text" required>
        <label for="correo">Correo </label>
        <input type="email" name="correo" id="correo" required>
        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required>
        <input id="buttonS" type="submit" name="submit" value="Registrate">
        <a id="buttonS" href="index.php">Inicia sesión</a>
    </form>
</body>
</html>
