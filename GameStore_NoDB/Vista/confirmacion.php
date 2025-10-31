<?php
// GameStore_NoDB/Vista/confirmacion.php
session_start();
include 'header.php';

$numero_pedido = isset($_GET['pedido_id']) ? htmlspecialchars($_GET['pedido_id']) : 'N/A';
?>

<div class="container mt-5 text-center">
    <div class="alert alert-success">
        <h4 class="alert-heading">¡Compra realizada con éxito!</h4>
        <p>Gracias por tu compra. Tu pedido está siendo procesado.</p>
        <hr>
        <p class="mb-0"><strong>Número de Pedido:</strong> <?php echo $numero_pedido; ?></p>
    </div>

    <!-- Aquí se podría mostrar un resumen de la compra si se pasa la info -->

    <a href="catalogo.php" class="btn btn-primary mt-3">Seguir Comprando</a>
    <a href="mis_compras.php" class="btn btn-secondary mt-3">Ver Mis Compras</a>
</div>

<?php include 'footer.php'; ?>
