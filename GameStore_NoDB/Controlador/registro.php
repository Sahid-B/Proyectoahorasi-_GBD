<?php
// GameStore_NoDB/Controlador/registro.php
require_once '../Modelo/memoria.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol'];

    // Check if email already exists
    foreach ($_SESSION['db']['usuarios'] as $usuario) {
        if ($usuario['correo'] === $correo) {
            header("Location: ../Vista/registro.php?error=correo_existente");
            exit();
        }
    }

    // Add new user
    $id = $_SESSION['db']['next_user_id']++;
    $_SESSION['db']['usuarios'][$id] = [
        'id_usuario' => $id,
        'nombre' => $nombre,
        'correo' => $correo,
        'contraseña' => password_hash($contraseña, PASSWORD_DEFAULT),
        'rol' => $rol,
    ];

    header("Location: ../Vista/login.php?registro=exitoso");
    exit();
}
?>
