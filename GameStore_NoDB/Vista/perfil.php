<?php
// GameStore_NoDB/Vista/perfil.php
session_start();
require_once '../Modelo/Database.php';
include 'header.php';

// Proteger esta página
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$user_id = $_SESSION['id_usuario'];
$user_info = $db->getUsuarioPorId($user_id);
$user_stats = $db->getEstadisticasUsuario($user_id);
?>

<div class="container mt-5">
    <h2>Mi Perfil</h2>

    <!-- Mensajes de feedback -->
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <h4>Información Actual</h4>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user_info['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['correo']); ?></p>

            <h4 class="mt-4">Estadísticas</h4>
            <p><strong>Total de Compras:</strong> <?php echo $user_stats['total_compras'] ?? 0; ?></p>
            <p><strong>Dinero Gastado:</strong> $<?php echo number_format($user_stats['dinero_gastado'] ?? 0, 2); ?></p>
        </div>
        <div class="col-md-6">
            <h4>Editar Información</h4>
            <form action="../Controlador/usuario_acciones.php" method="post">
                <input type="hidden" name="action" value="update_profile">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_info['nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Email</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($user_info['correo']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña">
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
