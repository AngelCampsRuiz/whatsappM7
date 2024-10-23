<?php
// Configuración de la base de datos en español
$host = "localhost";
$usuario = "root";
$contrasena = "";
$nombre_bd = "whatsapp_espanol";

// Conexión a la base de datos
$con = mysqli_connect($host, $usuario, $contrasena, $nombre_bd);

if (mysqli_connect_errno()) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
?>
