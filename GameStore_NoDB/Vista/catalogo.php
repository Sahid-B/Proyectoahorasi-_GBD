<?php
// GameStore_NoDB/Vista/catalogo.php
session_start();
require_once '../Modelo/Database.php';
include 'header.php';

$db = new Database();

// --- Obtener datos iniciales ---
$juegos = $db->getJuegos();
$generos = $db->getGeneros();

// --- Lógica de filtrado y ordenación ---

// Filtrar por término de búsqueda
$busqueda = isset($_GET['busqueda']) ? strtolower(trim($_GET['busqueda'])) : '';
if ($busqueda) {
    $juegos = array_filter($juegos, function($juego) use ($busqueda) {
        return strpos(strtolower($juego['titulo']), $busqueda) !== false;
    });
}

// Filtrar por género
$genero_filtro = isset($_GET['genero']) ? $_GET['genero'] : '';
if ($genero_filtro) {
    $juegos = array_filter($juegos, function($juego) use ($genero_filtro) {
        return $juego['genero'] === $genero_filtro;
    });
}

// Ordenar resultados
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'nombre_asc';
usort($juegos, function($a, $b) use ($orden) {
    switch ($orden) {
        case 'precio_asc':
            return $a['precio'] <=> $b['precio'];
        case 'precio_desc':
            return $b['precio'] <=> $a['precio'];
        case 'nombre_desc':
            return strcasecmp($b['titulo'], $a['titulo']);
        case 'nombre_asc':
        default:
            return strcasecmp($a['titulo'], $b['titulo']);
    }
});

// --- Lógica de paginación ---
$juegos_por_pagina = 12;
$total_juegos = count($juegos);
$total_paginas = $total_juegos > 0 ? ceil($total_juegos / $juegos_por_pagina) : 1;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_actual = max(1, min($pagina_actual, $total_paginas));
$offset = ($pagina_actual - 1) * $juegos_por_pagina;
$juegos_en_pagina = array_slice($juegos, $offset, $juegos_por_pagina);
?>

<div class="container mt-5">
    <h2>Catálogo de Juegos</h2>
    <p class="text-muted">Se encontraron <?php echo $total_juegos; ?> juegos.</p>

    <!-- Formulario de filtro y ordenación -->
    <form action="catalogo.php" method="GET" class="mb-4 p-3 bg-light border rounded">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="busqueda" class="form-label">Buscar por nombre</label>
                <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="E.g., Cyberpunk" value="<?php echo htmlspecialchars($busqueda); ?>">
            </div>
            <div class="col-md-3">
                <label for="genero" class="form-label">Género</label>
                <select class="form-select" id="genero" name="genero">
                    <option value="">Todos</option>
                    <?php foreach ($generos as $g): ?>
                        <option value="<?php echo htmlspecialchars($g); ?>" <?php if ($g === $genero_filtro) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($g); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ordenar por</label>
                <select class="form-select" name="orden">
                    <option value="nombre_asc" <?php if ($orden === 'nombre_asc') echo 'selected'; ?>>Nombre (A-Z)</option>
                    <option value="nombre_desc" <?php if ($orden === 'nombre_desc') echo 'selected'; ?>>Nombre (Z-A)</option>
                    <option value="precio_asc" <?php if ($orden === 'precio_asc') echo 'selected'; ?>>Precio (Bajo a Alto)</option>
                    <option value="precio_desc" <?php if ($orden === 'precio_desc') echo 'selected'; ?>>Precio (Alto a Bajo)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Cuadrícula de juegos -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (empty($juegos_en_pagina)): ?>
            <div class="col-12">
                <p class="text-center">No se encontraron juegos que coincidan con los filtros.</p>
            </div>
        <?php else: ?>
            <?php foreach ($juegos_en_pagina as $juego): ?>
                <div class="col">
                    <div class="card h-100">
                        <a href="detalle_juego.php?id=<?php echo $juego['id_juego']; ?>">
                            <img src="<?php echo htmlspecialchars($juego['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($juego['titulo']); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($juego['titulo']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($juego['genero']); ?></p>
                            <p class="card-text h4">$<?php echo number_format($juego['precio'], 2); ?></p>
                        </div>
                        <div class="card-footer">
                            <form action="../Controlador/carrito_acciones.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id_juego" value="<?php echo $juego['id_juego']; ?>">
                                <button type="submit" class="btn btn-primary w-100">Añadir al Carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?php if ($i === $pagina_actual) echo 'active'; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($busqueda); ?>&genero=<?php echo urlencode($genero_filtro); ?>&orden=<?php echo urlencode($orden); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include 'footer.php'; ?>
