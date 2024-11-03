<?php
session_start();
include('./paginasInternas/db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit();
}
//Crea una consulta SQL para seleccionar todas las amistades donde el usuario actual es id_usuario1 o id_usuario2. 
// permite encontrar todas las relaciones de amistad .

$id_usuario = $_SESSION['id_usuario'];
$consulta = "SELECT * FROM amistades WHERE id_usuario1='$id_usuario' OR id_usuario2='$id_usuario'";
$resultado = mysqli_query($con, $consulta);

// Verificar si hay amigos y se acaba de ejecutar el script
if (mysqli_num_rows($resultado) == 0) {
    echo "No tienes amigos.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Amigos</title>
    <link rel="stylesheet" type="text/css" href="styles/stylesamigos.css">
</head>
<body>
    <a class="btn-inicio" href="inicio.php">←</a>
    <h1>Lista de Amigos</h1>
    <div class="amigos-container">
        <ul>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <?php
                //Si el usuario actual es id_usuario1, entonces el amigo es id_usuario2; de lo contrario, el amigo es id_usuario1
                $id_amigo = ($fila['id_usuario1'] == $id_usuario) ? $fila['id_usuario2'] : $fila['id_usuario1'];
                //Crea una consulta SQL para seleccionar el nombre de usuario y el nombre real del amigo
                $consulta_amigo = "SELECT usuario, nombre_real FROM usuarios WHERE id='$id_amigo'";
                $resultado_amigo = mysqli_query($con, $consulta_amigo);
                $amigo = mysqli_fetch_assoc($resultado_amigo);
                ?>
                <li>
                 <!-- muestra el nombre del amigo -->
                    <?= htmlspecialchars($amigo['usuario']) ?> 
                    <a class="btn-chatear" href="chat.php?id_amigo=<?= $id_amigo ?>">Chatear</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>

