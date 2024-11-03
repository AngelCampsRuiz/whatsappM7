<?php
session_start();
include('./paginasInternas/db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busqueda = mysqli_real_escape_string($con, $_POST['busqueda']);

    // Consulta para buscar usuarios que coincidan con la búsqueda segun nombre de usuario o filas
    $consulta = "SELECT * FROM usuarios 
                 WHERE (usuario LIKE '%$busqueda%' OR nombre_real LIKE '%$busqueda%') 
                 AND id != '$id_usuario'";
    $resultado = mysqli_query($con, $consulta);

    // Consulta para obtener las amistades del usuario actual
    $consulta_amistades = "SELECT id_usuario1, id_usuario2 FROM amistades WHERE id_usuario1='$id_usuario' OR id_usuario2='$id_usuario'";
    $resultado_amistades = mysqli_query($con, $consulta_amistades);

    // Almacenar los IDs de los amigos Si $fila['id_usuario1'] coincide con $id_usuario ( el usuario actual es id_usuario1 en la fila de amistad), entonces se agrega a $amistades el ID del otro usuario (id_usuario2).
//De lo contrario, el usuario actual es id_usuario2, por lo que se agrega id_usuario1 como amigo., while mostrara resultados por filas
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
    <!-- Si aparece mas de una resultado -->

    <?php if (isset($resultado) && mysqli_num_rows($resultado) > 0): ?>
        <h2>Resultados de la Búsqueda</h2>
        <ul class="resultado-lista">
            <!-- mientras se obtengan resultados -->
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <li class="resultado-item">
                    <!-- mostrar el valor de usuario en negrita -->
                    <span class="usuario-info">
                        <strong><?= htmlspecialchars($fila['usuario']) ?></strong> - <?= htmlspecialchars($fila['nombre_real']) ?>
                    </span>
                    <!-- si ya son amigos -->
                    <?php if (in_array($fila['id'], $amistades)): ?>
                        <span>(Ya son amigos)</span>
                    <?php else: ?>
                        <a href="./paginasInternas/enviar_solicitud.php?id=<?= $fila['id'] ?>" class="enviar-solicitud">
                            <button class="boton-solicitud">Enviar Solicitud de Amistad</button>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
        <!-- si no aperece mas de un resultado -->
    <?php elseif (isset($resultado)): ?>
        <h2>No se encontraron usuarios.</h2>
    <?php endif; ?>
</body>
</html>
