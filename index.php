<?php
session_start();
include('./paginasInternas/db.php');

// Arreglo para guardar mensajes de error específicos de cada campo
$errores = [
    'usuario' => '',
    'nombre_real' => '',
    'correo' => '',
    'contrasena' => ''
];

// Redirigir a la página principal si ya está logueado
if (isset($_SESSION['id_usuario'])) {
    header("Location: ./inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($con, $_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    if (isset($_POST['registrar'])) {
        // Registro de nuevo usuario
        $nombre_real = mysqli_real_escape_string($con, $_POST['nombre_real']);
        $correo = mysqli_real_escape_string($con, $_POST['correo']);

        // Validaciones
        if (empty($usuario) || !preg_match("/^[a-zA-Z\s]+$/", $usuario)) {
            $errores['usuario'] = "El nombre de usuario no puede estar vacío y solo puede contener letras y espacios.";
        }
        if (empty($nombre_real) || !preg_match("/^[a-zA-Z\s]+$/", $nombre_real)) {
            $errores['nombre_real'] = "El nombre real no puede estar vacío y solo puede contener letras y espacios.";
        }
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = "Por favor, introduce un correo electrónico válido.";
        }
        if (empty($contrasena) || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{4,}$/", $contrasena)) {
            $errores['contrasena'] = "La contraseña debe tener al menos 4 caracteres, una mayúscula, una minúscula y un número.";
        }

        // Si no hay errores, proceder con el registro
        if (empty(array_filter($errores))) {
            $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

            $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' OR correo='$correo'";
            $resultado = mysqli_query($con, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                $errores['usuario'] = "El nombre de usuario o el correo electrónico ya están registrados.";
            } else {
                $consulta = "INSERT INTO usuarios (usuario, nombre_real, correo, contrasena) VALUES ('$usuario', '$nombre_real', '$correo', '$contrasena_hash')";
                if (mysqli_query($con, $consulta)) {
                    echo "Registro exitoso. Ahora puedes iniciar sesión.";
                } else {
                    $errores['general'] = "Hubo un error en el registro.";
                }
            }
        }
    } elseif (isset($_POST['iniciar_sesion'])) {
        // Inicio de sesión
        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = mysqli_query($con, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario_bd = mysqli_fetch_assoc($resultado);
            if (password_verify($contrasena, $usuario_bd['contrasena'])) {
                $_SESSION['id_usuario'] = $usuario_bd['id'];
                header("Location: inicio.php");
                exit();
            } else {
                $errores['contrasena'] = "La contraseña es incorrecta.";
            }
        } else {
            $errores['usuario'] = "El usuario no existe.";
        }
    }
}

// Variable para indicar al script si debe desplazarse al formulario de registro
$desplazarRegistro = !empty(array_filter($errores));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login y Registro</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <link rel="stylesheet" type="text/css" href="styles/auth.css"> 
    <script src="index.js"></script>
    <style>
        .hidden {
            display: none; 
        }
        .visible {
            display: block; 
        }
    </style>

</head>
<body>
    <div class="auth-container">
        <div id="login-form" class="visible">
            <h1>Iniciar Sesión</h1>
            <form method="POST" action="index.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario" required>
                <br>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <br>
                <input type="submit" name="iniciar_sesion" value="Iniciar Sesión">
            </form>
            <div class="toggle-link" onclick="toggleForms()">¿No tienes cuenta? Regístrate aquí</div>
        </div>

        <div id="register-form" class="hidden">
            <h1>Registro</h1>
            <form id="registerForm" method="POST" action="index.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario" >
                <?php if (!empty($errores['usuario'])): ?>
                    <span class="error"><?php echo $errores['usuario']; ?></span>
                <?php endif; ?>
                <br>
                <input type="text" name="nombre_real" placeholder="Nombre real" >
                <?php if (!empty($errores['nombre_real'])): ?>
                    <span class="error"><?php echo $errores['nombre_real']; ?></span>
                <?php endif; ?>
                <br>
                <input type="email" name="correo" placeholder="Correo electrónico" >
                <?php if (!empty($errores['correo'])): ?>
                    <span class="error"><?php echo $errores['correo']; ?></span>
                <?php endif; ?>
                <br>
                <input type="password" name="contrasena" placeholder="Contraseña" >
                <?php if (!empty($errores['contrasena'])): ?>
                    <span class="error"><?php echo $errores['contrasena']; ?></span>
                <?php endif; ?>
                <br>
                <input type="submit" name="registrar" value="Registrar">
            </form>
            <div class="toggle-link" onclick="toggleForms()">¿Ya tienes cuenta? Inicia sesión aquí</div>
        </div>
    </div>

    <script src="index.js"></script>
    <script>
        // Si $desplazarRegistro es verdadero, hacer scroll al formulario de registro
        <?php if ($desplazarRegistro): ?>
            document.addEventListener('DOMContentLoaded', function () {
                toggleForms(); // Muestra el formulario de registro
                window.scrollTo({
                    top: document.getElementById('registerForm').offsetTop,
                    behavior: 'smooth'
                });
            });
        <?php endif; ?>
    </script>
</body>
</html>
