<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busqueda = mysqli_real_escape_string($con, $_POST['busqueda']);

    // Consulta para buscar usuarios que coincidan con la búsqueda
    $consulta = "SELECT * FROM usuarios 
                 WHERE (usuario LIKE '%$busqueda%' OR nombre_real LIKE '%$busqueda%') 
                 AND id != '$id_usuario'";
    $resultado = mysqli_query($con, $consulta);

    // Consulta para obtener las amistades del usuario actual
    $consulta_amistades = "SELECT id_usuario1, id_usuario2 FROM amistades WHERE id_usuario1='$id_usuario' OR id_usuario2='$id_usuario'";
    $resultado_amistades = mysqli_query($con, $consulta_amistades);

    // Almacenar los IDs de los amigos
    $amistades = [];
    while ($fila = mysqli_fetch_assoc($resultado_amistades)) {
        $amistades[] = $fila['id_usuario1'] === $id_usuario ? $fila['id_usuario2'] : $fila['id_usuario1'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Usuarios</title>
    <link rel="stylesheet" type="text/css" href="styles/styleschat.css">
</head>
<body>
    <a class="btn-inicio" href="inicio.php">←</a>

    <h1>Buscar Usuarios</h1>
    
    <div class="form-container">
        <form method="POST" action="buscar.php">
            <input type="text" name="busqueda" placeholder="Buscar usuario o nombre..." required>
            <input type="submit" value="Buscar">
        </form>
    </div>

    <?php if (isset($resultado) && mysqli_num_rows($resultado) > 0): ?>
        <h2>Resultados de la Búsqueda</h2>
        <ul class="resultado-lista">
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <li class="resultado-item">
                    <span class="usuario-info">
                        <strong><?= htmlspecialchars($fila['usuario']) ?></strong> - <?= htmlspecialchars($fila['nombre_real']) ?>
                    </span>
                    <?php if (in_array($fila['id'], $amistades)): ?>
                        <span>(Ya son amigos)</span>
                    <?php else: ?>
                        <a href="enviar_solicitud.php?id=<?= $fila['id'] ?>" class="enviar-solicitud">
                            <button class="boton-solicitud">Enviar Solicitud de Amistad</button>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif (isset($resultado)): ?>
        <h2>No se encontraron usuarios.</h2>
    <?php endif; ?>
</body>
</html>
