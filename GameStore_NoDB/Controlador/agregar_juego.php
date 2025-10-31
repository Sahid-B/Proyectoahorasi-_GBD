<?php
// GameStore_NoDB/Controlador/agregar_juego.php
session_start();
require_once '../Modelo/Database.php';

// Verificar que el usuario sea admin o vendedor
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_vendedor = $_SESSION['id_usuario'];

    // Valores por defecto o a mejorar (ej. subida de imagen)
    $rating = rand(3, 5); // Rating aleatorio por ahora
    $imagen = "https://placehold.co/400x550/1a1a2e/e94560?text=" . urlencode($titulo);

    $db = new Database();
    $exito = $db->crearJuego($titulo, $genero, $precio, $stock, $id_vendedor, $rating, $imagen);

    if ($exito) {
        header("Location: ../Vista/panel.php?exito=juego_agregado");
    } else {
        header("Location: ../Vista/panel.php?error=juego_no_agregado");
    }
    exit();
}
?>
