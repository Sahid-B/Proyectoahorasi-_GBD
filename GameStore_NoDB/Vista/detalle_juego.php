<?php
// GameStore_NoDB/Vista/detalle_juego.php
session_start();
require_once '../Modelo/Database.php';
include 'header.php';

$db = new Database();
$juego_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$juego = null;

if ($juego_id > 0) {
    $juego = $db->getJuegoPorId($juego_id);
}

if (!$juego) {
    echo "<div class='container mt-5'><p class='alert alert-danger'>Juego no encontrado.</p></div>";
    include 'footer.php';
    exit();
}

// Obtener todos los juegos para la sección de relacionados
$todos_los_juegos = $db->getJuegos();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($juego['imagen']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($juego['titulo']); ?>">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($juego['titulo']); ?></h2>
            <p><strong>Género:</strong> <span class="badge bg-secondary"><?php echo htmlspecialchars($juego['genero']); ?></span></p>
            <p><strong>Rating:</strong> <?php echo str_repeat('★', $juego['rating']) . str_repeat('☆', 5 - $juego['rating']); ?> </p>
            <p><strong>En stock:</strong> <?php echo htmlspecialchars($juego['stock']); ?> unidades</p>
            <p>Una breve descripción del juego iría aquí. Sumérgete en una aventura épica con gráficos impresionantes y una jugabilidad inmersiva.</p>
            <h3 class="text-neon">$<?php echo number_format($juego['precio'], 2); ?></h3>

            <form action="../Controlador/carrito_acciones.php" method="post" class="mt-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                <div class="row">
                    <div class="col-md-4">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" value="1" min="1" max="<?php echo $juego['stock']; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg mt-3" <?php echo $juego['stock'] <= 0 ? 'disabled' : ''; ?>>
                    <?php echo $juego['stock'] > 0 ? 'Añadir al Carrito' : 'Sin Stock'; ?>
                </button>
            </form>
        </div>
    </div>

    <hr class="mt-5">

    <h3 class="mt-4">Juegos Relacionados</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php
        // Filtrar juegos relacionados (mismo género, diferente ID)
        $related_games = array_filter($todos_los_juegos, function($related_juego) use ($juego) {
            return $related_juego['genero'] === $juego['genero'] && $related_juego['id_juego'] !== $juego['id_juego'];
        });

        // Mostrar hasta 4 juegos relacionados
        foreach (array_slice($related_games, 0, 4) as $related):
        ?>
            <div class="col">
                <div class="card h-100">
                     <a href="detalle_juego.php?id=<?php echo $related['id_juego']; ?>">
                        <img src="<?php echo htmlspecialchars($related['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related['titulo']); ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($related['titulo']); ?></h5>
                        <p class="card-text text-muted">$<?php echo number_format($related['precio'], 2); ?></p>
                    </div>
                    <div class="card-footer">
                         <a href="detalle_juego.php?id=<?php echo $related['id_juego']; ?>" class="btn btn-secondary w-100">Ver Detalles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
