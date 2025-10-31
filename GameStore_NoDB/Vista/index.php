<?php
include 'header.php';
require_once '../Modelo/memoria.php';

$juegos = $_SESSION['db']['juegos'];
// Get a few games for the carousel (e.g., first 3)
$featured_games = array_slice($juegos, 0, 3);
// Get some best-selling and new games for other sections
$best_selling_games = array_slice($juegos, 3, 4);
$new_games = array_slice($juegos, 7, 4);
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($featured_games as $index => $game): ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($featured_games as $index => $game): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $game['image']; ?>" class="d-block w-100" style="object-fit: cover; height: 500px; filter: brightness(0.5);" alt="<?php echo htmlspecialchars($game['titulo']); ?>">
                <div class="carousel-caption d-none d-md-block text-start">
                    <h1 class="display-4"><?php echo htmlspecialchars($game['titulo']); ?></h1>
                    <p class="lead">El juego de <?php echo htmlspecialchars($game['genero']); ?> mejor valorado ya disponible.</p>
                    <a href="catalogo.php" class="btn btn-primary btn-lg">Ver en el Catálogo</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Game Sections -->
<div class="container mt-5">
    <!-- Best Selling -->
    <h2 class="mb-4">Más Vendidos</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($best_selling_games as $game): ?>
            <div class="col">
                <div class="card h-100 game-card">
                    <img src="<?php echo $game['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($game['titulo']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($game['titulo']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($game['genero']); ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-neon">$<?php echo htmlspecialchars($game['precio']); ?></span>
                        <a href="#" class="btn btn-primary btn-sm">Añadir al Carrito</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- New Releases -->
    <h2 class="mb-4 mt-5">Nuevos Lanzamientos</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
       <?php foreach ($new_games as $game): ?>
            <div class="col">
                <div class="card h-100 game-card">
                    <img src="<?php echo $game['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($game['titulo']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($game['titulo']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($game['genero']); ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-neon">$<?php echo htmlspecialchars($game['precio']); ?></span>
                        <a href="#" class="btn btn-primary btn-sm">Añadir al Carrito</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>
.game-card { border: none; background: #1e1e3f; }
.game-card .card-footer { background: transparent; border-top: 1px solid var(--card-border); }
.text-neon { color: var(--neon-accent); }
</style>
