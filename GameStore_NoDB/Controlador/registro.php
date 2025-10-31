<?php
// GameStore_NoDB/Controlador/registro.php
session_start();
require_once '../Modelo/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol']; // 'cliente' o 'vendedor'

    $db = new Database();

    // 1. Verificar si el correo ya existe
    $usuarioExistente = $db->getUsuarioPorCorreo($correo);
    if ($usuarioExistente) {
        header("Location: ../Vista/registro.php?error=correo_existente");
        exit();
    }

    // 2. Crear el nuevo usuario
    $exito = $db->crearUsuario($nombre, $correo, $contraseña, $rol);

    if ($exito) {
        header("Location: ../Vista/login.php?registro=exitoso");
        exit();
    } else {
        header("Location: ../Vista/registro.php?error=desconocido");
        exit();
    }
}
?>
