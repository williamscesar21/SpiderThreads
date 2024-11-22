<?php
include("config.php");
session_start();

if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
    
    // Obtener el nombre del usuario
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 
    $stmt->bind_result($nombre_usuario); 
    $stmt->fetch(); 
    $stmt->close(); 

    // Obtener usuarios para mostrar en el dashboard
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

// Manejo de edición de comentarios
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
        echo "Error al actualizar el comentario";
    }

    $stmt->close(); 
}

// Manejo de eliminación de comentarios
if (isset($_POST['eliminar']) && isset($_POST['id_comentario'])) {
    $id_comentario = $_POST['id_comentario'];

    $stmt_eliminar = $conn->prepare("DELETE FROM comentarios WHERE id = ? AND id_usuario = ?");
    $stmt_eliminar->bind_param("ii", $id_comentario, $_SESSION['id']);
    if ($stmt_eliminar->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error al eliminar el comentario";
    }
    $stmt_eliminar->close();
}

// Manejo de publicaciones
if (isset($_POST["submit"])) {
    $contenido = trim($_POST["contenido"]); 

    if (!empty($contenido)) {
        $stmt = $conn->prepare("INSERT INTO publicaciones (id_usuario, contenido) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $contenido); 
        $stmt->execute(); 
        $stmt->close(); 
        header("Location: ".$_SERVER['PHP_SELF']);
    } else {
        $errorP = "El contenido no puede estar vacío";
    }
}

// Manejo de comentarios en publicaciones
if (isset($_POST["submitComment"])) {
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
        header("Location: index.php");
    } else {
        header("Location: index.php");
        exit();
    }
}

// Manejo de visualización de comentarios
if (isset($_POST["verComment"])) {
    $id_publicacion = $_POST["comenatarioId"];
    $mostrar = true;
} else {
    $mostrar = false;
}

// Obtener comentarios
$stmt = $conn->prepare("SELECT id_publicacion, contenido, fecha_actualizacion FROM comentarios ORDER BY fecha_actualizacion DESC");
$stmt->execute();
$result = $stmt->get_result();
$comentarios = array();
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

// Obtener publicaciones
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
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
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
            <a id="fecha" href="admin.php"><p id="">2024</p></a>
        <?php else: ?>
            <p id="fecha">2024</p>
        <?php endif; ?>
    </div>
    <div id="main">
        <div id="titulo">
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <h1>BIENVENIDO ADMIN</h1>
                <img src="spider.png" id="spider" alt="">
            <?php else: ?>
                <h1>SpiderThreads</h1>
                <img src="spider.png" id="spider" alt="">
            <?php endif; ?>
        </div>
        <div id="newPost">
            <p><?php echo "@$nombre_usuario"; ?></p>
            <form action="" method="post">
                <textarea id="textArea" name="contenido" placeholder="What's happening?!" rows="4" cols="50"></textarea>
                <br>
                <input id="buttonP" type="submit" name="submit" value="Post">
            </form>
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
                                <?php if ($comentario['id_usuario'] == $_SESSION['id']): ?>
                                        <form id="eliminarForm<?php echo $comentario['id']; ?>" method="post" action="">
                                            <input type="hidden" name="id_comentario" value="<?php echo $comentario['id']; ?>">
                                            <input id="eliminar" type="submit" name="eliminar" value="Eliminar comentario">
                                        </form>
                                        <button id="editarCo" <?php echo $comentario['id']; ?> onclick="toggleForm('<?php echo $comentario['id']; ?>')">Editar comentario</button>
                                        <div id="actualizarDatos<?php echo $comentario['id']; ?>" style="display: none;">
                                        <?php if ($comentario['id_usuario'] == $_SESSION['id']): ?>
                                            <form method="post" action="">
                                                <input type="hidden" name="id_comentario" value="<?php echo $comentario['id']; ?>">
                                                <input type="text" name="nuevo_comentario" value="<?php echo $comentario['contenido']; ?>">
                                                <input id="editarCo" type="submit" name="editComment" value="Editar comentario">
                                            </form>
                                        <?php endif; ?>
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
                <a id="cerrarComentarios" href="dashboard.php">Cerrar</a>
            </div>
            <a id="popup-overlay" class="popup-overlay" style="display: block;" href="index.php"></a>
        <?php 
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
        <div id ```php
        <div id="asideFooter">SpiderThreads C.A. 2024</div>
    </div>
</div>
</body>
</html>