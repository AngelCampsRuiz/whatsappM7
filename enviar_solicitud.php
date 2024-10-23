<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_remitente = $_SESSION['id_usuario'];
$id_receptor = $_GET['id'];

// Verificar si la solicitud ya existe
$consulta = "SELECT * FROM solicitudes_amistad WHERE id_remitente='$id_remitente' AND id_receptor='$id_receptor'";
$resultado = mysqli_query($con, $consulta);

if (mysqli_num_rows($resultado) == 0) {
    // Insertar la solicitud de amistad
    $insertar = "INSERT INTO solicitudes_amistad (id_remitente, id_receptor) VALUES ('$id_remitente', '$id_receptor')";
    mysqli_query($con, $insertar);
    echo "Solicitud de amistad enviada.";
} else {
    echo "Ya has enviado una solicitud a este usuario.";
}

header("Location: buscar.php");
?>
