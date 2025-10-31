<?php
// GameStore_NoDB/Controlador/admin_acciones.php
session_start();

// Proteger estas acciones
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Vista/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: ../Vista/admin_dashboard.php');
    exit();
}

$action = $_POST['action'];

switch ($action) {
    case 'change_role':
        $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
        $nuevo_rol = isset($_POST['nuevo_rol']) ? $_POST['nuevo_rol'] : '';

        if ($id_usuario > 0 && ($nuevo_rol === 'admin' || $nuevo_rol === 'cliente')) {
            // Prevenir que el admin se quite el rol a sí mismo
            if ($id_usuario == $_SESSION['id_usuario'] && $nuevo_rol !== 'admin') {
                // No permitir
            } else {
                $_SESSION['db']['usuarios'][$id_usuario]['rol'] = $nuevo_rol;
            }
        }
        header('Location: ../Vista/admin_usuarios.php');
        break;

    case 'delete_user':
        $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;

        // Prevenir que el admin se elimine a sí mismo
        if ($id_usuario > 0 && $id_usuario != $_SESSION['id_usuario']) {
            unset($_SESSION['db']['usuarios'][$id_usuario]);
        }
        header('Location: ../Vista/admin_usuarios.php');
        break;

    default:
        header('Location: ../Vista/admin_dashboard.php');
        break;
}
exit();
?>
