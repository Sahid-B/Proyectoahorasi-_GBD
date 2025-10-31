<?php
include 'header.php';
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: login.php');
    exit();
}
if (!isset($_GET['id']) || !isset($_SESSION['db']['juegos'][$_GET['id']])) {
    header('Location: panel.php');
    exit();
}
$id_juego = $_GET['id'];
$juego = $_SESSION['db']['juegos'][$id_juego];
if ($_SESSION['rol'] === 'vendedor' && $juego['id_vendedor'] !== $_SESSION['id_usuario']) {
    header('Location: panel.php?error=no_autorizado');
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="text-center mb-4">Editar Juego</h2>
                <form action="../Controlador/editar_juego.php" method="POST">
                    <input type="hidden" name="id_juego" value="<?php echo htmlspecialchars($juego['id_juego']); ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($juego['titulo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <input type="text" class="form-control" id="genero" name="genero" value="<?php echo htmlspecialchars($juego['genero']); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($juego['precio']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($juego['stock']); ?>" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Juego</button>
                        <a href="panel.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
