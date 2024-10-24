<?php
session_start();
include('db.php');

// Si el usuario ya ha iniciado sesión, redirigir a la página principal
if (isset($_SESSION['id_usuario'])) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registrar'])) {
        // Registro de nuevo usuario
        $usuario = mysqli_real_escape_string($con, $_POST['usuario']);
        $nombre_real = mysqli_real_escape_string($con, $_POST['nombre_real']);
        $correo = mysqli_real_escape_string($con, $_POST['correo']);
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); // Encriptar la contraseña

        // Verificar si el usuario o el correo ya existen
        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' OR correo='$correo'";
        $resultado = mysqli_query($con, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            echo "El nombre de usuario o el correo electrónico ya están registrados.";
        } else {
            $consulta = "INSERT INTO usuarios (usuario, nombre_real, correo, contrasena) 
                         VALUES ('$usuario', '$nombre_real', '$correo', '$contrasena')";
            if (mysqli_query($con, $consulta)) {
                echo "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                echo "Hubo un error en el registro.";
            }
        }
    } elseif (isset($_POST['iniciar_sesion'])) {
        // Inicio de sesión
        $usuario = mysqli_real_escape_string($con, $_POST['usuario']);
        $contrasena = $_POST['contrasena'];

        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = mysqli_query($con, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $usuario_bd = mysqli_fetch_assoc($resultado);
            if (password_verify($contrasena, $usuario_bd['contrasena'])) {
                $_SESSION['id_usuario'] = $usuario_bd['id'];
                header("Location: inicio.php");
            } else {
                echo "La contraseña es incorrecta.";
            }
        } else {
            echo "El usuario no existe.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login y Registro</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <link rel="stylesheet" type="text/css" href="styles/auth.css"> <!-- Nueva línea para cargar el CSS -->
    <style>
        /* Agregar estilos directamente para simplificar */
        .hidden {
            display: none; /* Ocultar completamente el formulario */
        }
        .visible {
            display: block; /* Mostrar el formulario */
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div id="login-form" class="visible">
            <h1>Iniciar Sesión</h1>
            <form method="POST" action="index.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario" required><br>
                <input type="password" name="contrasena" placeholder="Contraseña" required><br>
                <input type="submit" name="iniciar_sesion" value="Iniciar Sesión">
            </form>
            <div class="toggle-link" onclick="toggleForms()">¿No tienes cuenta? Regístrate aquí</div>
        </div>

        <div id="register-form" class="hidden">
            <h1>Registro</h1>
            <form method="POST" action="index.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario" required><br>
                <input type="text" name="nombre_real" placeholder="Nombre real" required><br>
                <input type="email" name="correo" placeholder="Correo electrónico" required><br>
                <input type="password" name="contrasena" placeholder="Contraseña" required><br>
                <input type="submit" name="registrar" value="Registrar">
            </form>
            <div class="toggle-link" onclick="toggleForms()">¿Ya tienes cuenta? Inicia sesión aquí</div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            // Alternar visibilidad entre los formularios
            if (loginForm.classList.contains("visible")) {
                loginForm.classList.remove("visible");
                loginForm.classList.add("hidden");
                registerForm.classList.remove("hidden");
                registerForm.classList.add("visible");
            } else {
                registerForm.classList.remove("visible");
                registerForm.classList.add("hidden");
                loginForm.classList.remove("hidden");
                loginForm.classList.add("visible");
            }
        }
    </script>
</body>

</html>
