<?php
include 'header.php';
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: login.php');
    exit();
}

$id_cliente = $_SESSION['id_usuario'];
$historial = [];
foreach ($_SESSION['db']['transacciones'] as $transaccion) {
    if ($transaccion['id_cliente'] == $id_cliente) {
        $detalles_completos = [];
        foreach ($_SESSION['db']['detalle_transacciones'] as $detalle) {
            if ($detalle['id_transaccion'] == $transaccion['id_transaccion']) {
                $juego_info = $_SESSION['db']['juegos'][$detalle['id_juego']] ?? ['titulo' => 'Juego Eliminado'];
                $detalle['titulo'] = $juego_info['titulo'];
                $detalles_completos[] = $detalle;
            }
        }
        $transaccion['detalles'] = $detalles_completos;
        $historial[$transaccion['id_transaccion']] = $transaccion;
    }
}
krsort($historial);
?>

<h1 class="mb-4">Mi Historial de Compras</h1>

<?php if (isset($_GET['compra']) && $_GET['compra'] === 'exitosa'): ?>
    <div class="alert alert-success">¡Gracias por tu compra! Tu pedido ha sido procesado.</div>
<?php endif; ?>

<?php if (empty($historial)): ?>
    <div class="card card-body text-center"><p class="mb-0">Aún no has realizado ninguna compra.</p></div>
<?php else: ?>
    <div class="accordion" id="accordionCompras">
        <?php foreach ($historial as $id_transaccion => $transaccion): ?>
            <div class="card mb-2">
                <div class="card-header" id="heading-<?php echo $id_transaccion; ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-start collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $id_transaccion; ?>">
                            Transacción #<?php echo $id_transaccion; ?> |
                            Fecha: <?php echo date('d/m/Y', strtotime($transaccion['fecha'])); ?> |
                            <span class="text-neon">Total: $<?php echo number_format($transaccion['total'], 2); ?></span>
                        </button>
                    </h2>
                </div>
                <div id="collapse-<?php echo $id_transaccion; ?>" class="collapse" data-bs-parent="#accordionCompras">
                    <div class="card-body">
                        <table class="table">
                            <thead><tr><th>Juego</th><th>Cantidad</th><th class="text-end">Subtotal</th></tr></thead>
                            <tbody>
                                <?php foreach ($transaccion['detalles'] as $detalle): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detalle['titulo']); ?></td>
                                        <td><?php echo $detalle['cantidad']; ?></td>
                                        <td class="text-end text-neon">$<?php echo number_format($detalle['subtotal'], 2); ?></td>
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

<?php include 'footer.php'; ?>
<style>
.accordion .card { border: 1px solid var(--card-border); }
.accordion .btn-link { text-decoration: none; font-weight: 600; width: 100%; }
.text-neon { color: var(--neon-accent); font-weight: bold; }
</style>
