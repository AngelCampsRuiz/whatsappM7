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

// Verificar si ya son amigos
$consulta_amistad = "SELECT * FROM amistades WHERE (id_usuario1='$id_remitente' AND id_usuario2='$id_receptor') OR (id_usuario1='$id_receptor' AND id_usuario2='$id_remitente')";
$resultado_amistad = mysqli_query($con, $consulta_amistad);

if (mysqli_num_rows($resultado_amistad) > 0) {
    echo "Ya son amigos.";
} else {
    // Verificar si la solicitud ya existe
    $consulta = "SELECT * FROM solicitudes_amistad WHERE id_remitente='$id_remitente' AND id_receptor='$id_receptor'";
    $resultado = mysqli_query($con, $consulta);

    if (mysqli_num_rows($resultado) == 0) {
        // Insertar la solicitud de amistad
        $insertar = "INSERT INTO solicitudes_amistad (id_remitente, id_receptor) VALUES ('$id_remitente', '$id_receptor')";
        if (mysqli_query($con, $insertar)) {
            echo "Solicitud de amistad enviada.";
        } else {
            echo "Error al enviar la solicitud.";
        }
    } else {
        echo "Ya has enviado una solicitud a este usuario.";
    }
}

// Redireccionar después de un pequeño retraso para permitir ver el mensaje
header("Location: ../buscar.php");
exit();
?>
