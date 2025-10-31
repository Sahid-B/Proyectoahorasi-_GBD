<?php
// GameStore_NoDB/Controlador/carrito_acciones.php
require_once '../Modelo/memoria.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: ../Vista/catalogo.php');
    exit();
}

$action = $_POST['action'];

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

switch ($action) {
    case 'add':
        $id_juego = isset($_POST['id_juego']) ? (int)$_POST['id_juego'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($id_juego > 0 && isset($_SESSION['db']['juegos'][$id_juego])) {
            $juego = $_SESSION['db']['juegos'][$id_juego];
            if (isset($_SESSION['carrito'][$id_juego])) {
                $_SESSION['carrito'][$id_juego]['cantidad'] += $cantidad;
            } else {
                $_SESSION['carrito'][$id_juego] = [
                    "titulo" => $juego['titulo'],
                    "precio" => $juego['precio'],
                    "image" => $juego['image'],
                    "cantidad" => $cantidad,
                    "stock" => $juego['stock']
                ];
            }
        }
        header('Location: ../Vista/carrito.php');
        break;

    case 'remove':
        $id_juego = isset($_POST['id_juego']) ? (int)$_POST['id_juego'] : 0;
        if (isset($_SESSION['carrito'][$id_juego])) {
            unset($_SESSION['carrito'][$id_juego]);
        }
        header('Location: ../Vista/carrito.php');
        break;

    case 'update':
        $id_juego = isset($_POST['id_juego']) ? (int)$_POST['id_juego'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
        if (isset($_SESSION['carrito'][$id_juego]) && $cantidad > 0) {
            $_SESSION['carrito'][$id_juego]['cantidad'] = min($cantidad, $_SESSION['carrito'][$id_juego]['stock']);
        }
        header('Location: ../Vista/carrito.php');
        break;

    case 'clear':
        $_SESSION['carrito'] = [];
        header('Location: ../Vista/carrito.php');
        break;

    default:
        header('Location: ../Vista/catalogo.php');
        break;
}
exit();
?>
