<?php
include 'header.php';
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Report 1: Total sales per seller
$ventas_vendedor = [];
foreach ($_SESSION['db']['transacciones'] as $transaccion) {
    foreach ($_SESSION['db']['detalle_transacciones'] as $detalle) {
        if ($detalle['id_transaccion'] == $transaccion['id_transaccion']) {
            $id_juego = $detalle['id_juego'];
            if (isset($_SESSION['db']['juegos'][$id_juego])) {
                $id_vendedor = $_SESSION['db']['juegos'][$id_juego]['id_vendedor'];
                $nombre_vendedor = $_SESSION['db']['usuarios'][$id_vendedor]['nombre'];
                $ventas_vendedor[$nombre_vendedor] = ($ventas_vendedor[$nombre_vendedor] ?? 0) + $detalle['subtotal'];
            }
        }
    }
}
arsort($ventas_vendedor);

// Report 2: Best-selling games
$juegos_vendidos = [];
foreach ($_SESSION['db']['detalle_transacciones'] as $detalle) {
    $titulo_juego = $_SESSION['db']['juegos'][$detalle['id_juego']]['titulo'] ?? 'Juego Eliminado';
    $juegos_vendidos[$titulo_juego] = ($juegos_vendidos[$titulo_juego] ?? 0) + $detalle['cantidad'];
}
arsort($juegos_vendidos);
$juegos_vendidos = array_slice($juegos_vendidos, 0, 10, true);
?>

<h1 class="mb-4">Reportes y Estadísticas</h1>

<div class="row">
    <!-- Sales by Seller -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h4>Ventas por Vendedor</h4></div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Vendedor</th><th class="text-end">Total Vendido</th></tr></thead>
                    <tbody>
                        <?php foreach ($ventas_vendedor as $vendedor => $total): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vendedor); ?></td>
                            <td class="text-end text-neon">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Best-Selling Games -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h4>Top 10 Juegos Más Vendidos</h4></div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Juego</th><th class="text-end">Unidades</th></tr></thead>
                    <tbody>
                        <?php foreach ($juegos_vendidos as $juego => $unidades): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($juego); ?></td>
                            <td class="text-end"><?php echo $unidades; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="panel.php" class="btn btn-secondary">Volver al Panel</a>
</div>

<?php include 'footer.php'; ?>
<style>.text-neon { color: var(--neon-accent); font-weight: bold; }</style>
