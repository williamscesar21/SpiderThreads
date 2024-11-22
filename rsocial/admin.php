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

if (isset($_POST["editComment"])) {
    $nuevo_comentario = $_POST["nuevo_comentario"];
    $id_comentario = $_POST["id_comentario"]; 

    $sql = "UPDATE comentarios SET contenido = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_comentario, $id_comentario); 

    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        //echo "Error al actualizar el comentario";
    }

    $stmt->close(); 
}

if(isset($_POST["submitComment"])) {
    $id_publicacion = $_POST["id_publicacion"];

    if (isset($_SESSION["id"])) {
        $id_usuario = $_SESSION["id"];

        $contenido_comentario = trim($_POST["comentario"]);

        if (!empty($contenido_comentario)) {

            $stmt = $conn->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, contenido) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_publicacion, $id_usuario, $contenido_comentario); 
            $stmt->execute(); 
            $stmt->close(); 
        } else {
            $errorComentario = "El comentario no puede estar vacío";
        }
        header("Location: admin.php");
    } else {
        header("Location: dashboard.php");
        exit();
    }
}


function enviarCorreoPublicacionEliminada($correo, $mensaje) {
    $asunto = "Publicación Eliminada - SpiderThreads";
    $cabeceras = "From: tuemail@dominio.com\r\n";
    mail($correo, $asunto, $mensaje, $cabeceras);
}

if(isset($_POST["eliminarPublicacion"])) {
    $id_publicacion = $_POST["id_publicacion"];

    $stmt_email = $conn->prepare("SELECT u.correo_electronico
                                  FROM publicaciones p
                                  INNER JOIN usuarios u ON p.id_usuario = u.id
                                  WHERE p.id = ?");
    $stmt_email->bind_param("i", $id_publicacion);
    $stmt_email->execute();
    $stmt_email->bind_result($correo_usuario);
    $stmt_email->fetch();
    $stmt_email->close();

    $stmt_get_contenido = $conn->prepare("SELECT contenido FROM publicaciones WHERE id = ?");
    $stmt_get_contenido->bind_param("i", $id_publicacion);
    $stmt_get_contenido->execute();
    $stmt_get_contenido->bind_result($contenido_publicacion);
    $stmt_get_contenido->fetch();
    $stmt_get_contenido->close();

    $stmt_eliminar_comentarios = $conn->prepare("DELETE FROM comentarios WHERE id_publicacion = ?");
    $stmt_eliminar_comentarios->bind_param("i", $id_publicacion);
    $stmt_eliminar_comentarios->execute();

    $stmt_eliminar_publicacion = $conn->prepare("DELETE FROM publicaciones WHERE id = ?");
    $stmt_eliminar_publicacion->bind_param("i", $id_publicacion);
    $stmt_eliminar_publicacion->execute();

    $mensaje = "Tu publicación ha sido eliminada debido a que incumple con las normas sociales de SpiderThreads.\n\nContenido de la publicación eliminada:\n\n$contenido_publicacion";
    enviarCorreoPublicacionEliminada($correo_usuario, $mensaje);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$stmt = $conn->prepare("SELECT id_usuario, id, contenido, fecha_actualizacion FROM publicaciones ORDER BY fecha_actualizacion DESC");
$stmt->execute();
$result = $stmt->get_result();
$publicaciones = array();
while ($row = $result->fetch_assoc()) {
    $publicaciones[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - SpiderThreads</title>
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
        <a id="fecha" href="admin2.php"><p id="">2024</p></a>
    <?php else: ?>
        <p id="fecha">2024</p>
    <?php endif; ?>
    
</div>



        <div id="main">
            <div id="titulo">
                <h1>Control de publicaciones</h1>
            </div>
            

<div id="Publicaciones">
    <?php foreach ($publicaciones as $publicacion): ?>
        <div id="Post">
            <div id="publicador">
                <?php 
                $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
                $stmt->bind_param("i", $publicacion['id_usuario']);
                $stmt->execute();
                $stmt->bind_result($nombre_usuario_publicador);
                $stmt->fetch();
                $stmt->close();
                echo "@$nombre_usuario_publicador";
                ?>
                <span id="horaP"><?php echo $publicacion['fecha_actualizacion']; ?></span>
            </div>
            <div id="contenido">
                <?php echo $publicacion['contenido']; ?>
            </div>
            <?php if ($rol === 'admin'): ?>
                <form method="post" action="">
                    <input type="hidden" name="id_publicacion" value="<?php echo $publicacion['id']; ?>">
                    <input type="submit" name="eliminarPublicacion" value="Eliminar publicación">
                </form>
            <?php endif; ?>
            <div id="comentarC">
                <form action="" method="post">
                    <input type="hidden" name="id_publicacion" value="<?php echo $publicacion['id']; ?>">
                    <textarea name="comentario" id="comentar" placeholder="Add Comment" cols="50" rows="1"></textarea>
                    <input id="buttonPcomment" type="submit" name="submitComment" value="Comment">
                </form>
                <form class="mostrarComentarios" action="" method="post">
                    <button id="leercomentarios" type="submit">Read comments</button>
                    <input type="hidden" name="id_publicacion" value="<?php echo $publicacion['id']; ?>">
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- --------------------------- MOSTRAR COMENTARIOS ---------------------------------------------------------------->

<?php
include("config.php");

function eliminarComentario($id_comentario, $conn) {
    $stmt_eliminar_comentario = $conn->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt_eliminar_comentario->bind_param("i", $id_comentario);
    if ($stmt_eliminar_comentario->execute()) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['id_publicacion'])) {
    $id_publicacion = $_POST['id_publicacion'];

    $stmt_publicacion = $conn->prepare("SELECT p.id_usuario, p.contenido, p.fecha_actualizacion, u.nombre_usuario 
                                        FROM publicaciones p 
                                        INNER JOIN usuarios u ON p.id_usuario = u.id 
                                        WHERE p.id = ?");
    $stmt_publicacion->bind_param("i", $id_publicacion);
    $stmt_publicacion->execute();
    $result_publicacion = $stmt_publicacion->get_result();
    $publicacion = $result_publicacion->fetch_assoc();

    $stmt = $conn->prepare("SELECT c.id, c.contenido, u.id as id_usuario, u.nombre_usuario, c.fecha_creacion 
                            FROM comentarios c 
                            INNER JOIN usuarios u ON c.id_usuario = u.id 
                            WHERE c.id_publicacion = ?");
    $stmt->bind_param("i", $id_publicacion);
    $stmt->execute();
    $result = $stmt->get_result();
?>
    <div id="popup" class="popup" style="display: block;">
        <div>
            <div class="publicacion">
                <div id="publicador"><?php echo $publicacion['nombre_usuario']; ?>
                    <span id="horaP"><?php echo $publicacion['fecha_actualizacion']; ?></span>
                </div>
                <div id="contenido"><?php echo $publicacion['contenido']; ?></div>
            </div>
            
            <div id="contenedorCo">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($comentario = $result->fetch_assoc()): ?>
                    <div id="comentario">
                        <div id="publicador"><?php echo $comentario['nombre_usuario']; ?>
                            <span id="horaP" class="fecha"><?php echo $comentario['fecha_creacion']; ?></span>
                        </div> 
                        <div id="contenido"><?php echo $comentario['contenido']; ?></div>
                        <form id="eliminarForm<?php echo $comentario['id']; ?>" method="post" action="">
                            <input type="hidden" name="id_comentario" value="<?php echo $comentario['id']; ?>">
                            <input id="eliminar" type="submit" name="eliminar" value="Eliminar comentario">
                        </form>
                        <?php if ($comentario['id_usuario'] == $_SESSION['id']): ?>
                            <button id="editarCo<?php echo $comentario['id']; ?>" onclick="toggleForm('<?php echo $comentario['id']; ?>')">Editar comentario</button>
                            <div id="actualizarDatos<?php echo $comentario['id']; ?>" style="display: none;">
                                
                                <form method="post" action="">
                                    <input type="hidden" name="id_comentario" value="<?php echo $comentario['id']; ?>">
                                    <input type="text" name="nuevo_comentario" value="<?php echo $comentario['contenido']; ?>">
                                    <input id="editarCo" type="submit" name="editComment" value="Editar comentario">
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div id="comentario">
                    <div id="publicador">Sin comentarios</div> 
                </div>
            <?php endif; ?>
        </div>
        </div>
        <a id="cerrarComentarios" href="admin.php">Cerrar</a>
    </div>
    <a id="popup-overlay" class="popup-overlay" style="display: block;" href="admin.php"></a>
<?php 
}


if (isset($_POST['eliminar']) && isset($_POST['id_comentario'])) {
    $id_comentario = $_POST['id_comentario'];

    
    if (eliminarComentario($id_comentario, $conn)) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error al eliminar el comentario.";
    }
}
?>

<script>
    function toggleForm(idComentario) {
        var formulario = document.getElementById("actualizarDatos" + idComentario);
        var boton = document.getElementById("editarCo" + idComentario);
        if (formulario.style.display === "none") {
            formulario.style.display = "block";
            boton.textContent = "Cancelar";
        } else {
            formulario.style.display = "none";
            boton.textContent = "Editar comentario";
        }
    }
</script>










<!----------------------- FIN DE MOSTRAR SECCION DE COMENTARIOS ----------------------------------------------------------->
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
                <div id="asideFooter" >SpiderThreads C.A. 2024</div>
        </div>
</body>
</html>

