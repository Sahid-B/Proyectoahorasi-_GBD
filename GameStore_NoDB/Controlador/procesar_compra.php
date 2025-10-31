<?php
// GameStore_NoDB/Controlador/procesar_compra.php
session_start();
require_once '../Modelo/Database.php';

// Verificar que el usuario esté logueado y sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario'])) {
    header('Location: ../Vista/login.php');
    exit();
}

// Verificar que el carrito no esté vacío
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
if (empty($carrito)) {
    header('Location: ../Vista/carrito.php?error=carrito_vacio');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$db = new Database();

// Calcular el total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Procesar la compra usando el método de la base de datos
$exito = $db->procesarCompra($id_usuario, $total, $carrito);

if ($exito) {
    // Si la compra es exitosa, vaciar el carrito
    $_SESSION['carrito'] = [];
    // Redirigir a una página de confirmación
    header("Location: ../Vista/mis_compras.php?exito=compra_realizada");
} else {
    // Si falla, redirigir al checkout con un error
    header("Location: ../Vista/checkout.php?error=procesamiento_fallido");
}
exit();
?>
