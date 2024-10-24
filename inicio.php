<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <h1>Bienvenido a Whatsapp Versión Web</h1>
    <nav>
        <a href="buscar.php">Buscar Usuarios</a><br>
        <a href="solicitudes.php">Solicitudes de Amistad</a><br>
        <a href="amigos.php">Lista de Amigos</a><br>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
    </nav>
</body>
</html>
