<?php
// GameStore_NoDB/Controlador/editar_juego.php
session_start();
require_once '../Modelo/Database.php';

// Verificar que el usuario sea admin o vendedor
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_juego = $_POST['id_juego'];
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];

    $db = new Database();
    $juego = $db->getJuegoPorId($id_juego);

    if ($juego) {
        // --- Verificación de autorización ---
        // Si el usuario es un vendedor, solo puede editar sus propios juegos.
        if ($_SESSION['rol'] === 'vendedor' && $juego['id_vendedor'] !== $_SESSION['id_usuario']) {
            header("Location: ../Vista/panel.php?error=no_autorizado");
            exit();
        }

        // --- Actualizar el juego ---
        $exito = $db->actualizarJuego($id_juego, $titulo, $genero, $precio, $stock);
        if ($exito) {
            header("Location: ../Vista/panel.php?exito=juego_editado");
        } else {
            header("Location: ../Vista/panel.php?error=edicion_fallida");
        }
    } else {
        header("Location: ../Vista/panel.php?error=no_encontrado");
    }
    exit();
}
?>
