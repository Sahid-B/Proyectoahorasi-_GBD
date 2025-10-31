<?php
session_start();
include 'header.php';
require_once '../Modelo/Database.php';

// --- Verificación de permisos ---
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$es_admin = ($_SESSION['rol'] === 'admin');
$id_vendedor_actual = $_SESSION['id_usuario'];

// --- Obtener los juegos correspondientes ---
if ($es_admin) {
    // El admin ve todos los juegos con el nombre del vendedor
    $juegos_panel = $db->getJuegosConVendedor();
} else {
    // El vendedor solo ve sus juegos
    $juegos_panel = $db->getJuegosPorVendedor($id_vendedor_actual);
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Panel de Administración</h1>
        <a href="agregar_juego.php" class="btn btn-primary">Añadir Nuevo Juego</a>
    </div>

    <!-- Mensajes de feedback -->
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">Operación realizada con éxito: <?php echo htmlspecialchars($_GET['exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error: <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                        <?php if (empty($juegos_panel)): ?>
                            <tr>
                                <td colspan="<?php echo $es_admin ? '7' : '6'; ?>" class="text-center">No tienes juegos para mostrar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($juegos_panel as $juego): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($juego['id_juego']); ?></td>
                                    <td><?php echo htmlspecialchars($juego['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($juego['genero']); ?></td>
                                    <td class="text-neon">$<?php echo number_format($juego['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($juego['stock']); ?></td>
                                    <?php if ($es_admin): ?>
                                        <td><?php echo htmlspecialchars($juego['vendedor_nombre']); ?></td>
                                    <?php endif; ?>
                                    <td class="text-end">
                                        <a href="editar_juego.php?id=<?php echo $juego['id_juego']; ?>" class="btn btn-secondary btn-sm">Editar</a>
                                        <a href="../Controlador/eliminar_juego.php?id=<?php echo $juego['id_juego']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este juego?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>.text-neon { color: var(--neon-accent); }</style>
