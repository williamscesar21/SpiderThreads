<?php
session_start(); 

include("config.php");


if (!isset($_SESSION["id"])) {    
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION["id"];

if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $stmt->bind_result($nombre_usuario); 
    $stmt->fetch(); 
    $stmt->close(); 

    $stmt = $conn->prepare("SELECT nombre_usuario, estado FROM usuarios WHERE id != ? ORDER BY estado ASC, nombre_usuario ASC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuarios = array();
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
} else {
    header("Location: index.php");
    exit();
}


if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $stmt = $conn->prepare("SELECT nombre_usuario, correo_electronico FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $stmt->bind_result($nombre_usuario, $correo_usuario); 
    $stmt->fetch(); 
    $stmt->close(); 
} else {
    header("Location: index.php");
    exit();
}


if (isset($_POST["eliminar"])) {
   
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
       
        session_unset();
        session_destroy();
       
    } else {
       
        echo "Error al eliminar el usuario";
    }

    $stmt->close(); 
}


if (isset($_POST["actualizar"])) {
    
    $nuevo_nombre = $_POST["nombre_nuevo"];
    $nuevo_correo = $_POST["correo_nuevo"];

    
    $sql = "UPDATE usuarios SET nombre_usuario = ?, correo_electronico = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nuevo_nombre, $nuevo_correo, $id_usuario);

    if ($stmt->execute()) {
        
        $_SESSION["usuario"] = $nuevo_nombre;
        $_SESSION["correo"] = $nuevo_correo;

      
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
      
        echo "Error al actualizar los datos del usuario";
    }

    $stmt->close();
}


if (isset($_POST["actualizar_pass"])) {
    
    $contrasena_actual = $_POST["contrasena_actual"];
    $nueva_contrasena = $_POST["nueva_contrasena"];
    $confirmar_contrasena = $_POST["confirmar_contrasena"];

   
    $sql = "SELECT contrasena FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        $stmt->bind_result($contrasena_hash);
        $stmt->fetch();

        
        if (password_verify($contrasena_actual, $contrasena_hash)) {
            if ($nueva_contrasena === $confirmar_contrasena) {
                $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $nueva_contrasena_hash, $id_usuario);

                if ($stmt->execute()) {
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Error al actualizar la contraseña";
                }

                $stmt->close();
            } else {
                
                //echo "La nueva contraseña y la confirmación no coinciden";
            }
        } else {
            
            //echo "La contraseña actual es incorrecta";
        }
    } else {
        
        //echo "Error: Usuario no encontrado";
    }

    $stmt->close(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - SpiderThreads</title>
    <link rel="stylesheet" href="css2109.css">
</head>
<body>
 <div id="dashboard">
    <div id="asideLeft">
        <img src="persona.png" id="persona" alt="Persona" >
        <p id="usuario"><?php echo $nombre_usuario; ?></p>
        <a id="buttonS" href="index.php"><img id="icono" src="pagina-de-inicio.png" alt=""><span id="text1">Home</span></a>
        <a id="buttonS" href="myPosts.php"><img id="icono" src="imagen.png" alt=""><span id="text2">My Posts</span></a>
        <a id="buttonS" href="account.php"><img id="icono" src="usuario.png" alt=""><span id="text3">Account</span></a>
        <a id="buttonSc" href="cerrarSesion.php"><img id="icono" src="salida.png" alt=""><span id="text4">Log Out</span></a>
        <img src="spider2.png" id="spider2" alt="">
        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <a id="fecha" href="admin.php"><p id="">2024</p></a>
        <?php else: ?>
            <p id="fecha">2024</p>
        <?php endif; ?>
    </div>




        <div id="main">
            <div id="tituloU">
                <h1>Configuración de Usuario</h1>
            </div>
            <div id="perfilUsuario">
                <p><strong>Nombre de Usuario:</strong> <?php echo ucwords($nombre_usuario); ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo ucwords($correo_usuario); ?></p>
            </div>

            <div id="actualizarDatos" style="display: none;">
                <form method="post" action="">
                    <label for="nombre_nuevo">Nuevo Nombre de Usuario:</label>
                    <input type="text" id="nombre_nuevo" name="nombre_nuevo" value="<?php echo ucfirst(strtolower($nombre_usuario)); ?>">
                    <br>
                    <label for="correo_nuevo">Nuevo Correo Electrónico:</label>
                    <input type="email" id="correo_nuevo" name="correo_nuevo" value="<?php echo $correo_usuario; ?>">
                    <br>
                    <button id="confirmar" type="submit" name="actualizar">Confirmar</button>
                </form>
            </div>

            <button id="mostrarAct" onclick="toggleForm()">Actualizar Datos</button>

            <script>
                function toggleForm() {
                    var formulario = document.getElementById("actualizarDatos");
                    var boton = document.getElementById("mostrarAct");
                    if (formulario.style.display === "none") {
                        formulario.style.display = "block";
                        boton.textContent = "Cancelar";
                    } else {
                        formulario.style.display = "none";
                        boton.textContent = "Actualizar Datos";
                    }
                }
            </script>

            <form id="eliminarForm" method="post" action="">
                <input type="hidden" name="eliminar" value="true">
            </form>

            <div id="actualizarPass" style="display: none;">
                <form method="post" action="">
                    <label for="contrasena_actual">Contraseña Actual:</label>
                    <input type="password" id="contrasena_actual" name="contrasena_actual">
                    <br>
                    <label for="nueva_contrasena">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contrasena" name="nueva_contrasena">
                    <br>
                    <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena">
                    <br>
                    <button id="confirmar_pass" type="submit" name="actualizar_pass">Confirmar</button>
                </form>
            </div>

            <button id="mostrarPass" onclick="togglePass()">Cambiar Contraseña</button>

            <script>
                function togglePass() {
                    var passForm = document.getElementById("actualizarPass");
                    var passButton = document.getElementById("mostrarPass");
                    if (passForm.style.display === "none") {
                        passForm.style.display = "block";
                        passButton.textContent = "Cancelar";
                    } else {
                        passForm.style.display = "none";
                        passButton.textContent = "Cambiar Contraseña";
                    }
                }
            </script>

            <div id="botonesUsuario">
                <button id="eliminarUsuario" onclick="confirmarEliminacion()">Eliminar Cuenta</button>

                <script>
                    function confirmarEliminacion() {
                        if (confirm("¿Estás seguro de que quieres eliminar tu cuenta?")) {
                            document.getElementById('eliminarForm').submit();
                        } else {
                            return false;
                        }
                    }
                </script>
            </div>
        </div>
        <div id="asideRight">
            <img src="spiderman3.png" id="spiderman3" alt="">
            <h2>Usuarios</h2>
            <div id="usuariosLi">
                <?php foreach ($usuarios as $usuario): ?>
                    <li>
                    <span class="circulo <?php echo $usuario['estado'] == 'enLinea' ? 'online' : 'offline'; ?>"></span>
                    <?php echo $usuario['nombre_usuario']; ?>
                    </li>
                <?php endforeach; ?>
            </div>
                <div id="asideFooter" >SpiderThreads C.A. 2024</div>
        </div>
    </div>
</body>
</html>
