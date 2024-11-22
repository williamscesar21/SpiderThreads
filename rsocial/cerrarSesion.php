<?php

session_start();
function establecerEstadoOffline($id) {
    include("config.php"); 

    $stmt = $conn->prepare("UPDATE usuarios SET estado = 'offline' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}


if (isset($_SESSION["id"])) {
    establecerEstadoOffline($_SESSION["id"]);

    session_unset();
    session_destroy();

    header("Location: index.php");
    exit(); 
} else {
    // Si no hay sesión activa, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit(); 
}
?>
