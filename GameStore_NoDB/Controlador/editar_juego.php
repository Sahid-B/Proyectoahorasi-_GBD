<?php
// GameStore_NoDB/Controlador/editar_juego.php
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_juego = $_POST['id_juego'];
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    if (isset($_SESSION['db']['juegos'][$id_juego])) {
        // Authorization check for sellers
        if ($_SESSION['rol'] === 'vendedor' && $_SESSION['db']['juegos'][$id_juego]['id_vendedor'] !== $_SESSION['id_usuario']) {
            header("Location: ../Vista/panel.php?error=no_autorizado");
            exit();
        }

        $_SESSION['db']['juegos'][$id_juego]['titulo'] = $titulo;
        $_SESSION['db']['juegos'][$id_juego]['genero'] = $genero;
        $_SESSION['db']['juegos'][$id_juego]['precio'] = $precio;
        $_SESSION['db']['juegos'][$id_juego]['stock'] = $stock;

        header("Location: ../Vista/panel.php?exito=juego_editado");
    } else {
        header("Location: ../Vista/panel.php?error=no_encontrado");
    }
    exit();
}
?>
