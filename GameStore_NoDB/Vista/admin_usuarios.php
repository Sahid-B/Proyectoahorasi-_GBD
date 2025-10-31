<?php
// GameStore_NoDB/Vista/admin_usuarios.php
session_start();
require_once '../Modelo/Database.php';

// Proteger esta página
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include 'header.php';

$db = new Database();
$usuarios = $db->getUsuarios();

// Buscador
$busqueda = isset($_GET['busqueda']) ? strtolower(trim($_GET['busqueda'])) : '';
if ($busqueda) {
    $usuarios = array_filter($usuarios, function($usuario) use ($busqueda) {
        return strpos(strtolower($usuario['nombre']), $busqueda) !== false || strpos(strtolower($usuario['correo']), $busqueda) !== false;
    });
}
?>

<div class="container mt-5">
    <h1>Gestionar Usuarios</h1>

    <!-- Mensajes de feedback -->
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="admin_usuarios.php" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre o email..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                        <td>
                             <!-- El admin no puede modificarse a sí mismo en esta interfaz -->
                            <?php if ($usuario['id_usuario'] != $_SESSION['id_usuario']): ?>
                                <!-- Cambiar Rol -->
                                <form action="../Controlador/admin_acciones.php" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres cambiar el rol de este usuario?');">
                                    <input type="hidden" name="action" value="change_role">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                    <select name="nuevo_rol" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="cliente" <?php if ($usuario['rol'] === 'cliente') echo 'selected'; ?>>Cliente</option>
                                        <option value="vendedor" <?php if ($usuario['rol'] === 'vendedor') echo 'selected'; ?>>Vendedor</option>
                                        <option value="admin" <?php if ($usuario['rol'] === 'admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                </form>
                                <!-- Eliminar Usuario -->
                                <form action="../Controlador/admin_acciones.php" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario? Esta acción no se puede deshacer.');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">No se puede modificar el usuario actual</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
