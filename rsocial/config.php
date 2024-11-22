<?php
$servername = "localhost";
$username = "williamscesar21";
$password = "williamscesar21";
$database = "rsocial";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error){
    die("Error de conexion a la base de datos: " . $conn->connect_error);
}

//echo "Conexion exitosa";

// Podemos crear consultas

// Cerramos la conexion
//$conn->close();

?>