<?php
// GameStore_NoDB/Controlador/procesar_compra.php
require_once '../Modelo/memoria.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario'])) {
    header('Location: ../Vista/checkout.php');
    exit();
}

$user_id = $_SESSION['id_usuario'];
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

if (empty($carrito)) {
    header('Location: ../Vista/carrito.php');
    exit();
}

// Calculate total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Create a new 'pedido' (order)
$pedido_id = $_SESSION['db']['next_transaccion_id']++;
$_SESSION['db']['transacciones'][$pedido_id] = [
    'pedido_id' => $pedido_id,
    'usuario_id' => $user_id,
    'total' => $total,
    'fecha' => date('Y-m-d H:i:s'),
    // Storing shipping info directly in the order for simplicity
    'nombre_completo' => $_POST['nombre_completo'],
    'direccion' => $_POST['direccion'],
    'telefono' => $_POST['telefono'],
    'metodo_pago' => $_POST['metodo_pago']
];

// Create 'pedidos_detalles'
foreach ($carrito as $id_juego => $item) {
    $detalle_id = $_SESSION['db']['next_detalle_id']++;
    $_SESSION['db']['detalle_transacciones'][$detalle_id] = [
        'detalle_id' => $detalle_id,
        'pedido_id' => $pedido_id,
        'juego_id' => $id_juego,
        'precio' => $item['precio'],
        'cantidad' => $item['cantidad']
    ];
}

// Clear the cart
$_SESSION['carrito'] = [];

// Redirect to confirmation page
header("Location: ../Vista/confirmacion.php?pedido_id=" . $pedido_id);
exit();
?>
