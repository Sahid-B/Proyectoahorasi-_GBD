<?php
// GameStore_NoDB/Vista/detalle_juego.php
require_once '../Modelo/memoria.php';
session_start();
include 'header.php';

// Obtener el ID del juego de la URL
$juego_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$juego = null;

if ($juego_id > 0 && isset($_SESSION['db']['juegos'][$juego_id])) {
    $juego = $_SESSION['db']['juegos'][$juego_id];
} else {
    echo "<div class='container mt-5'><p>Juego no encontrado.</p></div>";
    include 'footer.php';
    exit();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($juego['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($juego['titulo']); ?>">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($juego['titulo']); ?></h2>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($juego['genero']); ?></p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($juego['rating']); ?>/5</p>
            <p>Una breve descripción del juego iría aquí. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <h3>$<?php echo htmlspecialchars($juego['precio']); ?></h3>
            <form action="../Controlador/carrito_acciones.php" method="post">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" value="1" min="1" max="<?php echo $juego['stock']; ?>">
                </div>
                <button type="submit" class="btn btn-danger mt-3">Añadir al Carrito</button>
            </form>
        </div>
    </div>

    <hr class="mt-5">

    <h3 class="mt-4">Juegos Relacionados</h3>
    <div class="row">
        <?php
        $related_games = array_filter($_SESSION['db']['juegos'], function($related_juego) use ($juego) {
            return $related_juego['genero'] === $juego['genero'] && $related_juego['id_juego'] !== $juego['id_juego'];
        });

        $count = 0;
        foreach (array_slice($related_games, 0, 4) as $related) {
            echo "<div class='col-md-3'>";
            echo "<div class='card mb-4'>";
            echo "<img src='" . htmlspecialchars($related['image']) . "' class='card-img-top' alt='" . htmlspecialchars($related['titulo']) . "'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($related['titulo']) . "</h5>";
            echo "<p class='card-text'>$" . htmlspecialchars($related['precio']) . "</p>";
            echo "<a href='detalle_juego.php?id=" . $related['id_juego'] . "' class='btn btn-primary'>Ver más</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
