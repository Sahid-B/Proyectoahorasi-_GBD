<?php
// GameStore_NoDB/Vista/admin_dashboard.php
session_start();
require_once '../Modelo/memoria.php';

// Proteger esta página
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'header.php';

// --- Estadísticas ---
$total_ventas = array_sum(array_column($_SESSION['db']['transacciones'], 'total'));
$total_juegos = count($_SESSION['db']['juegos']);
$total_usuarios = count($_SESSION['db']['usuarios']);
$ventas_hoy = 0;
foreach ($_SESSION['db']['transacciones'] as $transaccion) {
    if (date('Y-m-d', strtotime($transaccion['fecha'])) === date('Y-m-d')) {
        $ventas_hoy += $transaccion['total'];
    }
}
$ultimas_compras = array_slice(array_reverse($_SESSION['db']['transacciones']), 0, 5);
?>

<div class="container mt-5">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total de Ventas</div>
                <div class="card-body">
                    <h5 class="card-title">$<?php echo number_format($total_ventas, 2); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total de Juegos</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_juegos; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total de Usuarios</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $total_usuarios; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Ventas de Hoy</div>
                <div class="card-body">
                    <h5 class="card-title">$<?php echo number_format($ventas_hoy, 2); ?></h5>
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
            <?php foreach ($ultimas_compras as $compra): ?>
                <tr>
                    <td><?php echo $compra['fecha']; ?></td>
                    <td><?php echo htmlspecialchars($_SESSION['db']['usuarios'][$compra['usuario_id']]['nombre']); ?></td>
                    <td>$<?php echo number_format($compra['total'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="mt-4">Enlaces Rápidos</h3>
    <a href="admin_usuarios.php" class="btn btn-secondary">Gestionar Usuarios</a>
    <a href="catalogo.php" class="btn btn-secondary">Gestionar Juegos</a>
</div>

<?php include 'footer.php'; ?>
