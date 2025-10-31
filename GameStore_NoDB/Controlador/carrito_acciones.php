<?php
// GameStore_NoDB/Controlador/carrito_acciones.php
session_start();
require_once '../Modelo/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header('Location: ../Vista/catalogo.php');
    exit();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$action = $_POST['action'];
$db = new Database();

switch ($action) {
    case 'add':
        $id_juego = isset($_POST['id_juego']) ? (int)$_POST['id_juego'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($id_juego > 0) {
            $juego = $db->getJuegoPorId($id_juego);
            if ($juego) {
                if (isset($_SESSION['carrito'][$id_juego])) {
                    // Si ya está en el carrito, actualizamos la cantidad
                    $_SESSION['carrito'][$id_juego]['cantidad'] += $cantidad;
                } else {
                    // Si no está, lo agregamos
                    $_SESSION['carrito'][$id_juego] = [
                        'id_juego' => $juego['id_juego'],
                        'titulo' => $juego['titulo'],
                        'precio' => $juego['precio'],
                        'imagen' => $juego['imagen'],
                        'cantidad' => $cantidad,
                        'stock' => $juego['stock'],
                    ];
                }
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
            // Asegurarse de no exceder el stock
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
