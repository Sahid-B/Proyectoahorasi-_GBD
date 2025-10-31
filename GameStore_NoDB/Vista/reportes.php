<?php
session_start();
include 'header.php';
require_once '../Modelo/Database.php';

// --- Seguridad ---
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$db = new Database();

// --- Obtener datos para los reportes ---
$ventas_vendedor = $db->getVentasPorVendedor();
$juegos_vendidos = $db->getJuegosMasVendidos(10);
?>

<div class="container mt-5">
    <h1 class="mb-4">Reportes y Estadísticas</h1>

    <div class="row">
        <!-- Ventas por Vendedor -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><h4>Ventas por Vendedor</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Vendedor</th><th class="text-end">Total Vendido</th></tr></thead>
                            <tbody>
                                <?php foreach ($ventas_vendedor as $venta): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($venta['vendedor']); ?></td>
                                    <td class="text-end fw-bold">$<?php echo number_format($venta['total_vendido'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 Juegos Más Vendidos -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><h4>Top 10 Juegos Más Vendidos</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Juego</th><th class="text-end">Unidades</th></tr></thead>
                            <tbody>
                                <?php foreach ($juegos_vendidos as $juego): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($juego['titulo']); ?></td>
                                    <td class="text-end fw-bold"><?php echo $juego['unidades_vendidas']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
    </div>
</div>

<?php include 'footer.php'; ?>
