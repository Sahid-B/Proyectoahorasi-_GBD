<?php
// GameStore_NoDB/Vista/perfil.php
session_start();
require_once '../Modelo/memoria.php';
include 'header.php';

// Proteger esta página
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id_usuario'];
$user_info = $_SESSION['db']['usuarios'][$user_id];

// --- Estadísticas de Compras ---
$total_compras = 0;
$dinero_gastado = 0;
foreach ($_SESSION['db']['transacciones'] as $transaccion) {
    if ($transaccion['usuario_id'] == $user_id) {
        $total_compras++;
        $dinero_gastado += $transaccion['total'];
    }
}
?>

<div class="container mt-5">
    <h2>Mi Perfil</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Información Actual</h4>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user_info['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['correo']); ?></p>

            <h4 class="mt-4">Estadísticas</h4>
            <p><strong>Total de Compras:</strong> <?php echo $total_compras; ?></p>
            <p><strong>Dinero Gastado:</strong> $<?php echo number_format($dinero_gastado, 2); ?></p>
        </div>
        <div class="col-md-6">
            <h4>Editar Información</h4>
            <form action="../Controlador/usuario_acciones.php" method="post">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-group mb-3">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_info['nombre']); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_info['correo']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
            <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#passwordModal">
                Cambiar Contraseña
            </button>
        </div>
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="passwordModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cambiar Contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Esta funcionalidad aún no está implementada.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
