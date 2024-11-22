<?php
session_start(); 
include("config.php");

if(!isset($_SESSION["recuperar"])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST["actualizar"])){
    $token = $_POST["token"];
    $nuevaContraseña = $_POST["nueva_contraseña"];

    $stmt = $conn->prepare("SELECT correo_electronico FROM tokens WHERE token = ? AND fecha_expiracion > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result(); 

    if($stmt->num_rows > 0){
        $stmt->bind_result($correo);
        $stmt->fetch();

        
        $contrasenaEncriptada = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

       
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE correo_electronico = ?");
        $stmt->bind_param("ss", $contrasenaEncriptada, $correo);
        $stmt->execute();

       
        $stmt = $conn->prepare("DELETE FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        header("Location: index.php");

    } else {
        
    }
    $_SESSION["recuperar"] = false;
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
    
    <form action="" id="rectangulo2" method="post">
        <label for="token">Código de Recuperación</label>
        <input type="text" name="token" id="token" required>
        <label for="nueva_contraseña">Nueva Contraseña</label>
        <input type="password" name="nueva_contraseña" id="nueva_contraseña" required>
        <input type="submit" id="buttonS" name="actualizar" value="Actualizar Contraseña">
    </form>
    
</body>
</html>
