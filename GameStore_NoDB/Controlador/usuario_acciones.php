<?php
// GameStore_NoDB/Controlador/usuario_acciones.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: ../Vista/index.php');
    exit();
}

$action = $_POST['action'];

switch ($action) {
    case 'update_profile':
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ../Vista/login.php');
            exit();
        }

        $user_id = $_SESSION['id_usuario'];
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);

        // Basic validation
        if (!empty($nombre) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['db']['usuarios'][$user_id]['nombre'] = $nombre;
            $_SESSION['db']['usuarios'][$user_id]['correo'] = $email;
            $_SESSION['nombre'] = $nombre; // Update session name as well
        }

        header('Location: ../Vista/perfil.php');
        break;

    default:
        header('Location: ../Vista/index.php');
        break;
}
exit();
?>
