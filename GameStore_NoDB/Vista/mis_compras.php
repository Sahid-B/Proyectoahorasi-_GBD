<?php
session_start();
include 'header.php';
require_once '../Modelo/Database.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$id_usuario = $_SESSION['id_usuario'];
$transacciones = $db->getTransaccionesPorUsuario($id_usuario);

?>
<div class="container mt-5">
    <h1 class="mb-4">Mi Historial de Compras</h1>

    <?php if (isset($_GET['exito']) && $_GET['exito'] === 'compra_realizada'): ?>
        <div class="alert alert-success">¡Gracias por tu compra! Tu pedido ha sido procesado.</div>
    <?php endif; ?>

    <?php if (empty($transacciones)): ?>
        <div class="card card-body text-center"><p class="mb-0">Aún no has realizado ninguna compra.</p></div>
    <?php else: ?>
        <div class="accordion" id="accordionCompras">
            <?php foreach ($transacciones as $transaccion):
                $detalles = $db->getDetallesTransaccion($transaccion['id_transaccion']);
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-<?php echo $transaccion['id_transaccion']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $transaccion['id_transaccion']; ?>">
                            Transacción #<?php echo $transaccion['id_transaccion']; ?> |
                            Fecha: <?php echo date('d/m/Y H:i', strtotime($transaccion['fecha'])); ?> |
                            <span class="fw-bold ms-2">Total: $<?php echo number_format($transaccion['total'], 2); ?></span>
                        </button>
                    </h2>
                    <div id="collapse-<?php echo $transaccion['id_transaccion']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCompras">
                        <div class="accordion-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Juego</th>
                                        <th>Cantidad</th>
                                        <th class="text-end">Precio Unitario</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalles as $detalle): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($detalle['titulo']); ?></td>
                                            <td><?php echo $detalle['cantidad']; ?></td>
                                            <td class="text-end">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                                            <td class="text-end fw-bold">$<?php echo number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
