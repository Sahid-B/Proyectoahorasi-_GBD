<?php
// GameStore_NoDB/Vista/admin_dashboard.php
session_start();
require_once '../Modelo/Database.php';

// Proteger esta página
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'header.php';

$db = new Database();
$stats = $db->getEstadisticasAdmin();
?>

<div class="container mt-5">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total de Ventas</div>
                <div class="card-body">
                    <h5 class="card-title">$<?php echo number_format($stats['total_ventas'] ?? 0, 2); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total de Juegos</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $stats['total_juegos'] ?? 0; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total de Usuarios</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $stats['total_usuarios'] ?? 0; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Ventas de Hoy</div>
                <div class="card-body">
                    <h5 class="card-title">$<?php echo number_format($stats['ventas_hoy'] ?? 0, 2); ?></h5>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Últimas 5 Compras</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($stats['ultimas_compras'])): ?>
                <tr><td colspan="3" class="text-center">No hay compras recientes.</td></tr>
            <?php else: ?>
                <?php foreach ($stats['ultimas_compras'] as $compra): ?>
                    <tr>
                        <td><?php echo $compra['fecha']; ?></td>
                        <td><?php echo htmlspecialchars($compra['nombre_usuario']); ?></td>
                        <td>$<?php echo number_format($compra['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 class="mt-4">Enlaces Rápidos</h3>
    <a href="admin_usuarios.php" class="btn btn-secondary">Gestionar Usuarios</a>
    <a href="panel.php" class="btn btn-secondary">Gestionar Juegos</a>
</div>

<?php include 'footer.php'; ?>
