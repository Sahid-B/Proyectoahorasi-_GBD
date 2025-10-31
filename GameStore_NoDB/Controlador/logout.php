<?php
// GameStore_NoDB/Controlador/logout.php
require_once '../Modelo/memoria.php';

// Clear user-specific session variables but keep the 'db'
unset($_SESSION['id_usuario']);
unset($_SESSION['nombre']);
unset($_SESSION['rol']);
unset($_SESSION['carrito']);

header("Location: ../Vista/index.php");
exit();
?>
