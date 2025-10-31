<?php
include 'header.php';
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: login.php');
    exit();
}

$es_admin = ($_SESSION['rol'] === 'admin');
$id_vendedor_actual = $_SESSION['id_usuario'];
$juegos_panel = [];
foreach ($_SESSION['db']['juegos'] as $juego) {
    if ($es_admin || $juego['id_vendedor'] == $id_vendedor_actual) {
        $vendedor_nombre = $_SESSION['db']['usuarios'][$juego['id_vendedor']]['nombre'] ?? 'N/A';
        $juego['vendedor_nombre'] = $vendedor_nombre;
        $juegos_panel[] = $juego;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Panel de Administración</h1>
    <a href="agregar_juego.php" class="btn btn-primary">Añadir Nuevo Juego</a>
</div>

<?php if (isset($_GET['exito'])): ?>
    <div class="alert alert-success">Operación realizada con éxito.</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error: <?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Género</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <?php if ($es_admin): ?>
                            <th>Vendedor</th>
                        <?php endif; ?>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($juegos_panel as $juego): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($juego['id_juego']); ?></td>
                            <td><?php echo htmlspecialchars($juego['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($juego['genero']); ?></td>
                            <td class="text-neon">$<?php echo htmlspecialchars($juego['precio']); ?></td>
                            <td><?php echo htmlspecialchars($juego['stock']); ?></td>
                            <?php if ($es_admin): ?>
                                <td><?php echo htmlspecialchars($juego['vendedor_nombre']); ?></td>
                            <?php endif; ?>
                            <td class="text-end">
                                <a href="editar_juego.php?id=<?php echo $juego['id_juego']; ?>" class="btn btn-secondary btn-sm">Editar</a>
                                <a href="../Controlador/eliminar_juego.php?id=<?php echo $juego['id_juego']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>.text-neon { color: var(--neon-accent); }</style>
