<?php
// GameStore_NoDB/Vista/carrito.php
session_start();
include 'header.php';

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total_general = 0;
?>

<div class="container mt-5">
    <h2>Carrito de Compras</h2>

    <?php if (empty($carrito)): ?>
        <div class="alert alert-info text-center">
            Tu carrito está vacío. <a href="catalogo.php">¡Empieza a comprar!</a>
        </div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Imagen</th>
                    <th scope="col">Juego</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($carrito as $id_juego => $item) {
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total_general += $subtotal;
                    ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>" style="width: 50px;"></td>
                        <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>
                            <form action="../Controlador/carrito_acciones.php" method="post" class="d-inline-flex">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id_juego" value="<?php echo $id_juego; ?>">
                                <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" max="<?php echo $item['stock']; ?>" class="form-control form-control-sm" style="width: 70px;">
                                <button type="submit" class="btn btn-secondary btn-sm ms-2">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form action="../Controlador/carrito_acciones.php" method="post" style="display:inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="id_juego" value="<?php echo $id_juego; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-right">
            <h3>Total: $<?php echo number_format($total_general, 2); ?></h3>
        </div>

        <div class="mt-4">
            <a href="catalogo.php" class="btn btn-secondary">Seguir Comprando</a>
            <form action="../Controlador/carrito_acciones.php" method="post" style="display:inline;">
                <input type="hidden" name="action" value="clear">
                <button type="submit" class="btn btn-warning">Vaciar Carrito</button>
            </form>
            <a href="checkout.php" class="btn btn-success">Procesar Compra</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
