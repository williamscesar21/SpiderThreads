<?php
include("config.php");
session_start();

if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $stmt = $conn->prepare("SELECT nombre_usuario, rol FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $stmt->bind_result($nombre_usuario, $rol); 
    $stmt->fetch(); 
    $stmt->close(); 

    if ($rol !== 'admin') {
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, nombre_usuario, correo_electronico, estado FROM usuarios WHERE id != ? ORDER BY estado ASC, nombre_usuario ASC");
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


if(isset($_POST["eliminarUsuario"])) {
    $id_usuario = $_POST["id_usuario"];

    
    $stmt_desactivar_fk = $conn->prepare("SET FOREIGN_KEY_CHECKS=0");
    $stmt_desactivar_fk->execute();
    $stmt_desactivar_fk->close();

    
    $stmt_eliminar_comentarios = $conn->prepare("DELETE FROM comentarios WHERE id_usuario = ?");
    $stmt_eliminar_comentarios->bind_param("i", $id_usuario);
    $stmt_eliminar_comentarios->execute();
    $stmt_eliminar_comentarios->close();

    
    $stmt_eliminar_publicaciones = $conn->prepare("DELETE FROM publicaciones WHERE id_usuario = ?");
    $stmt_eliminar_publicaciones->bind_param("i", $id_usuario);
    $stmt_eliminar_publicaciones->execute();
    $stmt_eliminar_publicaciones->close();

    
    $stmt_eliminar_usuario = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_eliminar_usuario->bind_param("i", $id_usuario);
    $stmt_eliminar_usuario->execute();
    $stmt_eliminar_usuario->close();

    
    $stmt_activar_fk = $conn->prepare("SET FOREIGN_KEY_CHECKS=1");
    $stmt_activar_fk->execute();
    $stmt_activar_fk->close();

    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


if(isset($_POST["actualizarNombre"])) {
    $id_usuario = $_POST["id_usuario"];
    $nuevo_nombre = $_POST["nuevo_nombre"];

    $stmt_actualizar_nombre = $conn->prepare("UPDATE usuarios SET nombre_usuario = ? WHERE id = ?");
    $stmt_actualizar_nombre->bind_param("si", $nuevo_nombre, $id_usuario);
    $stmt_actualizar_nombre->execute();
    $stmt_actualizar_nombre->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST["actualizarCorreo"])) {
    $id_usuario = $_POST["id_usuario"];

    if(isset($_POST["nuevo_correo"]) && !empty($_POST["nuevo_correo"])) {
        $nuevo_correo = $_POST["nuevo_correo"];

        $stmt_actualizar_correo = $conn->prepare("UPDATE usuarios SET correo_electronico = ? WHERE id = ?");
        $stmt_actualizar_correo->bind_param("si", $nuevo_correo, $id_usuario);
        $stmt_actualizar_correo->execute();
        $stmt_actualizar_correo->close();

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "El campo de correo electrónico está vacío";
    }
}

if(isset($_POST["actualizarRol"])) {
    $id_usuario = $_POST["id_usuario"];
    $nuevo_rol = $_POST["nuevo_rol"];

    $stmt_actualizar_rol = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt_actualizar_rol->bind_param("si", $nuevo_rol, $id_usuario);
    $stmt_actualizar_rol->execute();
    $stmt_actualizar_rol->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Usuarios - SpiderThreads</title>
    <link rel="stylesheet" href="css2109.css">
</head>
<body>
    <div id="dashboard">
    <div id="asideLeft">
    <img src="persona.png" id="persona" alt="Persona">
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
    <div id="titulo">
        <h1>Control de Usuarios</h1>
    </div>
    <div id="usuarios">
        <?php foreach ($usuarios as $usuario): ?>
            <div id="usuarioM">
                <p>Nombre de usuario: <strong><?php echo $usuario['nombre_usuario']; ?></strong></p>
                <p>Correo electrónico:<strong> <?php echo $usuario['correo_electronico']; ?></strong></p>
                <?php if ($usuario['estado'] === 'enLinea'): ?>
                    <p id="enLineaU" >El usuario está en línea</p>
                <?php endif; ?>
                
                
                <form action="" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                    <input type="text" name="nuevo_nombre" placeholder="Nombre" value="<?php echo $usuario['nombre_usuario']; ?>" >
                    <input type="submit" name="actualizarNombre" value="Actualizar nombre">
                </form>
                
               
                <form action="" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                    <input type="email" name="nuevo_correo" placeholder="Nuevo correo electrónico" value="<?php echo $usuario['correo_electronico']; ?>">
                    <input type="submit" name="actualizarCorreo" value="Actualizar correo">
                </form>
                
            
                <form action="" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                    <select id="editarCo" name="nuevo_rol">
                        <option value="admin">Select</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <input id="editarCo" type="submit" name="actualizarRol" value="Actualizar rol">
                </form>
                
              
                <form action="" method="post">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                    <input id="eliminar" type="submit" name="eliminarUsuario" value="Eliminar usuario">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>


        <div id="asideRight">
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a id="spiderman3" href="admin2.php"><img src="spiderman3.png" id="spiderman3" alt=""></a>
                <h2>Usuarios</h2>
            <?php else: ?>
                <img src="spiderman3.png" id="spiderman3" alt="">
                <h2>Usuarios</h2>
            <?php endif; ?>
            <div id="usuariosLi">
                <?php foreach ($usuarios as $usuario): ?>
                    <li>
                        <span class="circulo <?php echo $usuario['estado'] == 'enLinea' ? 'online' : 'offline'; ?>"></span>
                        <?php echo $usuario['nombre_usuario']; ?>
                    </li>
                <?php endforeach; ?>
            </div>
            <div id="asideFooter">SpiderThreads C.A. 2024</div>
        </div>
    </div>
</body>
</html>
