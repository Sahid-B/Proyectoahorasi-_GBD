<?php
// GameStore_NoDB/Controlador/eliminar_juego.php
session_start();
require_once '../Modelo/Database.php';

// Verificar que el usuario sea admin o vendedor
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../Vista/panel.php');
    exit();
}

$id_juego = $_GET['id'];

$db = new Database();
$juego = $db->getJuegoPorId($id_juego);

if ($juego) {
    // --- Verificación de autorización ---
    if ($_SESSION['rol'] === 'vendedor' && $juego['id_vendedor'] !== $_SESSION['id_usuario']) {
        header("Location: ../Vista/panel.php?error=no_autorizado");
        exit();
    }

    // --- Intentar eliminar el juego ---
    try {
        $exito = $db->eliminarJuego($id_juego);
        if ($exito) {
            header("Location: ../Vista/panel.php?exito=juego_eliminado");
        } else {
            header("Location: ../Vista/panel.php?error=eliminacion_fallida");
        }
    } catch (PDOException $e) {
        // Si hay una restricción de clave externa, no se podrá eliminar
        header("Location: ../Vista/panel.php?error=no_se_puede_eliminar");
    }
} else {
    header("Location: ../Vista/panel.php?error=no_encontrado");
}
exit();
?>
