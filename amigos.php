<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$consulta = "SELECT * FROM amistades WHERE id_usuario1='$id_usuario' OR id_usuario2='$id_usuario'";
$resultado = mysqli_query($con, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Amigos</title>
</head>
<body>
    <h1>Lista de Amigos</h1>
    <ul>
        <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
            <?php
            $id_amigo = ($fila['id_usuario1'] == $id_usuario) ? $fila['id_usuario2'] : $fila['id_usuario1'];
            $consulta_amigo = "SELECT usuario FROM usuarios WHERE id='$id_amigo'";
            $resultado_amigo = mysqli_query($con, $consulta_amigo);
            $amigo = mysqli_fetch_assoc($resultado_amigo);
            ?>
            <li>
                <?= $amigo['usuario'] ?> <a href="chat.php?id_amigo=<?= $id_amigo ?>">Chatear</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
