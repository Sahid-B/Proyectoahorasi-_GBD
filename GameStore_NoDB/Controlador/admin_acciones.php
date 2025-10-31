<?php
// GameStore_NoDB/Controlador/admin_acciones.php
session_start();
require_once '../Modelo/Database.php';

// --- Seguridad ---
// Solo administradores pueden acceder
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Vista/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header("Location: ../Vista/admin_dashboard.php");
    exit();
}

$db = new Database();
$action = $_POST['action'];

switch ($action) {
    case 'change_role':
        $id_usuario = $_POST['id_usuario'];
        $nuevo_rol = $_POST['nuevo_rol'];
        // Evitar que el admin se quite su propio rol
        if ($id_usuario == $_SESSION['id_usuario']) {
             header("Location: ../Vista/admin_usuarios.php?error=no_se_puede_cambiar_rol_propio");
             exit();
        }
        if ($db->cambiarRol($id_usuario, $nuevo_rol)) {
            header("Location: ../Vista/admin_usuarios.php?exito=rol_cambiado");
        } else {
            header("Location: ../Vista/admin_usuarios.php?error=desconocido");
        }
        break;

    case 'delete_user':
        $id_usuario = $_POST['id_usuario'];
        // Evitar que el admin se elimine a sí mismo
        if ($id_usuario == $_SESSION['id_usuario']) {
             header("Location: ../Vista/admin_usuarios.php?error=no_se_puede_eliminar_a_si_mismo");
             exit();
        }
        if ($db->eliminarUsuario($id_usuario)) {
            header("Location: ../Vista/admin_usuarios.php?exito=usuario_eliminado");
        } else {
            // Este error puede ocurrir si el usuario tiene transacciones asociadas
            header("Location: ../Vista/admin_usuarios.php?error=no_se_puede_eliminar_usuario_con_transacciones");
        }
        break;

    default:
        header("Location: ../Vista/admin_dashboard.php");
        break;
}
exit();
?>
