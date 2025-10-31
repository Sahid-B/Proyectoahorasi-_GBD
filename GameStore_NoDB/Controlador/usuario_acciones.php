<?php
// GameStore_NoDB/Controlador/usuario_acciones.php
session_start();
require_once '../Modelo/Database.php';

// --- Seguridad ---
// El usuario debe estar logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Vista/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header("Location: ../Vista/perfil.php");
    exit();
}

$db = new Database();
$action = $_POST['action'];
$id_usuario = $_SESSION['id_usuario'];

switch ($action) {
    case 'update_profile':
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña']; // Puede estar vacía

        // Validar que el nuevo correo no esté ya en uso por otro usuario
        $usuarioExistente = $db->getUsuarioPorCorreo($correo);
        if ($usuarioExistente && $usuarioExistente['id_usuario'] != $id_usuario) {
            header("Location: ../Vista/perfil.php?error=correo_existente");
            exit();
        }

        // Si se proporciona una nueva contraseña, actualizarla. Si no, mantener la actual.
        $exito = $contraseña ?
            $db->actualizarPerfil($id_usuario, $nombre, $correo, $contraseña) :
            $db->actualizarPerfil($id_usuario, $nombre, $correo);

        if ($exito) {
            // Actualizar los datos de la sesión
            $_SESSION['nombre'] = $nombre;
            header("Location: ../Vista/perfil.php?exito=perfil_actualizado");
        } else {
            header("Location: ../Vista/perfil.php?error=desconocido");
        }
        break;

    default:
        header("Location: ../Vista/perfil.php");
        break;
}
exit();
?>
