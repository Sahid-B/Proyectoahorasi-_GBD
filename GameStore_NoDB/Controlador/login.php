<?php
// GameStore_NoDB/Controlador/login.php
require_once '../Modelo/memoria.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    foreach ($_SESSION['db']['usuarios'] as $usuario) {
        if ($usuario['correo'] === $correo && password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'admin') {
                header("Location: ../Vista/admin_dashboard.php");
            } else {
                header("Location: ../Vista/index.php");
            }
            exit();
        }
    }

    // Si no se encuentra el usuario
    header("Location: ../Vista/login.php?error=1");
    exit();
}
?>
