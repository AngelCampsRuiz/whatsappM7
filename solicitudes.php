<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$consulta = "SELECT * FROM solicitudes_amistad WHERE id_receptor = '$id_usuario' AND estado = 'pendiente'";
$resultado = mysqli_query($con, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Amistad</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <h1>Solicitudes de Amistad</h1>
    <ul>
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
            <?php
            $id_remitente = $fila['id_remitente'];
            $consulta_remitente = "SELECT usuario FROM usuarios WHERE id='$id_remitente'";
            $resultado_remitente = mysqli_query($con, $consulta_remitente);
            $remitente = mysqli_fetch_assoc($resultado_remitente);
            ?>
            <li>
                <?= $remitente['usuario'] ?>
                <a href="aceptar_solicitud.php?id=<?= $fila['id'] ?>">Aceptar</a> |
                <a href="rechazar_solicitud.php?id=<?= $fila['id'] ?>">Rechazar</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
