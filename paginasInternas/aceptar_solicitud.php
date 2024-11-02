<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_solicitud = $_GET['id'];

// Obtener la solicitud de amistad
$consulta = "SELECT * FROM solicitudes_amistad WHERE id='$id_solicitud'";
$resultado = mysqli_query($con, $consulta);
$solicitud = mysqli_fetch_assoc($resultado);

if ($solicitud['id_receptor'] == $_SESSION['id_usuario']) {
    $id_remitente = $solicitud['id_remitente'];
    $id_receptor = $_SESSION['id_usuario'];

    // Aceptar la solicitud
    $consulta_aceptar = "UPDATE solicitudes_amistad SET estado='aceptada' WHERE id='$id_solicitud'";
    mysqli_query($con, $consulta_aceptar);

    // Crear la relación de amistad
    $consulta_amigos = "INSERT INTO amistades (id_usuario1, id_usuario2) VALUES ('$id_remitente', '$id_receptor')";
    mysqli_query($con, $consulta_amigos);

    echo "Solicitud aceptada.";
}

header("Location: ../solicitudes.php");
?>
