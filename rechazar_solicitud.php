<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_solicitud = $_GET['id'];

// Actualizar el estado de la solicitud a rechazada
$consulta_rechazar = "UPDATE solicitudes_amistad SET estado='rechazada' WHERE id='$id_solicitud'";
mysqli_query($con, $consulta_rechazar);

echo "Solicitud rechazada.";
header("Location: solicitudes.php");
?>
