<?php
// GameStore_NoDB/Controlador/login.php
session_start();
require_once '../Modelo/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $db = new Database();
    $usuario = $db->getUsuarioPorCorreo($correo);

    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        // Usuario autenticado correctamente
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: ../Vista/admin_dashboard.php");
        } else if ($usuario['rol'] === 'vendedor') {
            header("Location: ../Vista/panel.php");
        }
        else {
            header("Location: ../Vista/index.php");
        }
        exit();
    } else {
        // Error de autenticación
        header("Location: ../Vista/login.php?error=1");
        exit();
    }
}
?>
