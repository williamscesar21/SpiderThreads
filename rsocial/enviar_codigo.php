<?php
session_start(); 
include("config.php");

function generarToken() {
    return bin2hex(random_bytes(3)); 
}

function enviarCorreoRecuperacion($correo, $token) {
    $asunto = "Recuperación de Contraseña - SpiderThreads";
    $mensaje = "Para restablecer tu contraseña, ingresa el siguiente código en la página de actualización de contraseña: $token ";
    $cabeceras = "From: tuemail@dominio.com\r\n";
    mail($correo, $asunto, $mensaje, $cabeceras);
}


if(isset($_POST["submit"])){
    $correo = $_POST["correo"];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo_electronico = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $token = generarToken();

        $stmt = $conn->prepare("INSERT INTO tokens (correo_electronico, token, fecha_expiracion) VALUES (?, ?, NOW() + INTERVAL 5 MINUTE)");
        $stmt->bind_param("ss", $correo, $token);
        $stmt->execute();

        enviarCorreoRecuperacion($correo, $token);
        
        $_SESSION["recuperar"] = true;
        header("Location: confirmar_codigo.php?correo=$correo");
        exit();
    } else {
        echo "El correo electrónico ingresado no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css1.css">
    <title>Recuperar Contraseña - SpiderThreads</title>
</head>
<body>
    <div id="titulo">
        <h1>Recuperar Contraseña - SpiderThreads</h1>
        <img src="spider.png" id="spider" alt="">
    </div>
    
    <form action="" id="rectangulo1" method="post">
        <label for="correo">Correo Electrónico</label>
        <input type="email" name="correo" id="correo" required>
        <input id="buttonS" type="submit" name="submit" value="Enviar Código">
        <a href="index.php">Cancelar</a>
    </form>
    
</body>
</html>
