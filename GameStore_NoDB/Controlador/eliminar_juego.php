<?php
// GameStore_NoDB/Controlador/eliminar_juego.php
require_once '../Modelo/memoria.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header('Location: ../Vista/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../Vista/panel.php');
    exit();
}

$id_juego = $_GET['id'];

if (isset($_SESSION['db']['juegos'][$id_juego])) {
    // Authorization check for sellers
    if ($_SESSION['rol'] === 'vendedor' && $_SESSION['db']['juegos'][$id_juego]['id_vendedor'] !== $_SESSION['id_usuario']) {
        header("Location: ../Vista/panel.php?error=no_autorizado");
        exit();
    }

    // Check if the game is part of any transaction
    $en_transaccion = false;
    foreach ($_SESSION['db']['detalle_transacciones'] as $detalle) {
        if ($detalle['id_juego'] == $id_juego) {
            $en_transaccion = true;
            break;
        }
    }

    if ($en_transaccion) {
        header("Location: ../Vista/panel.php?error=no_se_puede_eliminar");
    } else {
        unset($_SESSION['db']['juegos'][$id_juego]);
        header("Location: ../Vista/panel.php?exito=juego_eliminado");
    }
} else {
    header("Location: ../Vista/panel.php?error=no_encontrado");
}
exit();
?>
