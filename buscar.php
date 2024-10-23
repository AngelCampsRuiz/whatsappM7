<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busqueda = mysqli_real_escape_string($con, $_POST['busqueda']);
    $id_usuario = $_SESSION['id_usuario'];

    $consulta = "SELECT * FROM usuarios 
                 WHERE (usuario LIKE '%$busqueda%' OR nombre_real LIKE '%$busqueda%') 
                 AND id != '$id_usuario'";
    $resultado = mysqli_query($con, $consulta);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Usuarios</title>
</head>
<body>
    <h1>Buscar Usuarios</h1>
    <form method="POST" action="buscar.php">
        <input type="text" name="busqueda" placeholder="Buscar usuario o nombre..." required>
        <input type="submit" value="Buscar">
    </form>

    <?php if (isset($resultado)): ?>
        <h2>Resultados de la Búsqueda</h2>
        <ul>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <li>
                    <?= $fila['usuario'] ?> - <?= $fila['nombre_real'] ?>
                    <a href="enviar_solicitud.php?id=<?= $fila['id'] ?>">Enviar Solicitud de Amistad</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
