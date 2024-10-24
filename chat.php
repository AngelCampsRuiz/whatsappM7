<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_amigo = $_GET['id_amigo'];

// Obtener el nombre real del amigo
$consulta_amigo = "SELECT nombre_real FROM usuarios WHERE id = '$id_amigo'";
$resultado_amigo = mysqli_query($con, $consulta_amigo);
$amigo = mysqli_fetch_assoc($resultado_amigo);

// Verificar si el amigo existe
if (!$amigo) {
    echo "El usuario no existe.";
    exit();
}

$nombre_amigo = $amigo['nombre_real'];

// Si se envía un mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mensaje = mysqli_real_escape_string($con, $_POST['mensaje']);
    
    // Verificar que el mensaje no esté vacío
    if (!empty($mensaje)) {
        $consulta = "INSERT INTO mensajes (id_remitente, id_receptor, mensaje) 
                     VALUES ('$id_usuario', '$id_amigo', '$mensaje')";
        
        // Ejecutar la consulta e imprimir errores si existen
        if (!mysqli_query($con, $consulta)) {
            echo "Error en la consulta: " . mysqli_error($con);
        }

        // Redirigir de nuevo al chat para evitar reenvíos accidentales
        header("Location: chat.php?id_amigo=$id_amigo");
        exit();
    }
}

// Consulta para obtener los mensajes entre los dos usuarios
$consulta_mensajes = "SELECT * FROM mensajes 
                      WHERE (id_remitente='$id_usuario' AND id_receptor='$id_amigo') 
                      OR (id_remitente='$id_amigo' AND id_receptor='$id_usuario') 
                      ORDER BY fecha DESC";

$resultado_mensajes = mysqli_query($con, $consulta_mensajes);

// Verificar errores en la consulta
if (!$resultado_mensajes) {
    echo "Error en la consulta de mensajes: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat con <?= htmlspecialchars($nombre_amigo) ?></title>
    <link rel="stylesheet" type="text/css" href="styles/styleschat.css">
    <script>
        // Recargar la página cada 5 segundos para obtener nuevos mensajes
        setInterval(function() {
            window.location.reload();
        }, 5000);
    </script>
</head>
<body>
    <h1>Chat con <?= htmlspecialchars($nombre_amigo) ?></h1>
    <div class="chat-container">
        <!-- Mostrar los mensajes -->
        <?php while ($fila = mysqli_fetch_assoc($resultado_mensajes)): ?>
            <p class="mensaje <?= ($fila['id_remitente'] == $id_usuario) ? 'mensaje-usuario' : 'mensaje-amigo' ?>">
                <strong><?= ($fila['id_remitente'] == $id_usuario) ? 'Tú' : htmlspecialchars($nombre_amigo) ?>:</strong> <?= htmlspecialchars($fila['mensaje']) ?>
            </p>
        <?php endwhile; ?>
    </div>

    <!-- Formulario para enviar mensajes -->
    <form method="POST" action="chat.php?id_amigo=<?= $id_amigo ?>">
        <textarea name="mensaje" placeholder="Escribe tu mensaje..." maxlength="250" required></textarea><br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
