<?php
// GameStore_NoDB/Controlador/agregar_juego.php
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_vendedor = $_SESSION['id_usuario'];

    $id = $_SESSION['db']['next_juego_id']++;
    $_SESSION['db']['juegos'][$id] = [
        'id_juego' => $id,
        'titulo' => $titulo,
        'genero' => $genero,
        'precio' => $precio,
        'stock' => $stock,
        'id_vendedor' => $id_vendedor,
    ];

    header("Location: ../Vista/panel.php?exito=juego_agregado");
    exit();
}
?>
