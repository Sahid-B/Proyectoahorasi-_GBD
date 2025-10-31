<?php
// GameStore_NoDB/Vista/checkout.php
session_start();
include 'header.php';

// Proteger esta página
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
if (empty($carrito)) {
    header("Location: carrito.php");
    exit();
}

$total_general = 0;
foreach ($carrito as $item) {
    $total_general += $item['precio'];
}
?>

<div class="container mt-5">
    <h2>Checkout</h2>
    <div class="row">
        <div class="col-md-7">
            <h4>Resumen del Pedido</h4>
            <ul class="list-group mb-3">
                <?php foreach ($carrito as $item): ?>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                        </div>
                        <span class="text-muted">$<?php echo number_format($item['precio'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>$<?php echo number_format($total_general, 2); ?></strong>
                </li>
            </ul>
        </div>
        <div class="col-md-5">
            <h4>Información de Envío</h4>
            <form action="../Controlador/procesar_compra.php" method="post">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select class="form-control" id="metodo_pago" name="metodo_pago">
                        <option>Tarjeta de Crédito</option>
                        <option>PayPal</option>
                        <option>Efectivo</option>
                    </select>
                </div>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Confirmar Compra</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
