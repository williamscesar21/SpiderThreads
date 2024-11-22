<?php
include("config.php");

function establecerEstadoEnLinea($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET estado = 'enLinea' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close(); 
}

// Comprobar si hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["id"])) {
    
    header("Location: dashboard.php");
    exit(); 
}

$mensaje = null;

if (isset($_POST["submit"])) {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    
    $stmt = $conn->prepare("SELECT id, contrasena, rol FROM usuarios WHERE correo_electronico = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($id, $contrasenaEncriptada, $rol);
    $stmt->fetch();
    $stmt->close(); 

    
    if (password_verify($clave, $contrasenaEncriptada)) {
        session_start();
        $_SESSION["id"] = $id;
        $_SESSION["rol"] = $rol; 

        establecerEstadoEnLinea($id);

        header("Location: dashboard.php");
        exit();
    } else {
        $mensaje = "Correo o contraseña incorrectos";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css1.css">
    <title>SpiderThreads</title>
</head>
<body>
    <div id="titulo">
        <h1>SpiderThreads</h1>
        <img src="spider.png" id="spider" alt="">
    </div>
    
    <form action="" id="rectangulo1" method="post">
        <h3>Inicia Sesión</h3>
        <label for="usuario">Correo</label>
        <input name="usuario" id="usuario" type="text" required>
        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required>

        <input id="buttonS" type="submit" name="submit" value="Iniciar Sesión">
        <p><?php echo $mensaje; ?></p>
        <p>Si no tienes una cuenta, <a href="register.php">Regístrate</a></p>
        <p>¿Olvidaste tu contraseña? <a href="enviar_codigo.php">Recupérala</a></p>
    </form>
    
</body>
</html>
