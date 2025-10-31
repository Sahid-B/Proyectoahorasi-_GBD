<?php
// GameStore_NoDB/Modelo/Database.php

require_once 'conexion.php';

class Database {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    // --- Métodos para Usuarios ---

    /**
     * Obtiene un usuario por su correo electrónico.
     * @param string $correo
     * @return mixed Devuelve el usuario si se encuentra, o false si no.
     */
    public function getUsuarioPorCorreo($correo) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un usuario por su ID.
     * @param int $id_usuario
     * @return mixed
     */
    public function getUsuarioPorId($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param string $nombre
     * @param string $correo
     * @param string $password
     * @param string $rol
     * @return bool Devuelve true si la creación fue exitosa, false si no.
     */
    public function crearUsuario($nombre, $correo, $password, $rol) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $correo, $hash, $rol]);
    }

    /**
     * Obtiene todos los usuarios.
     * @return array
     */
    public function getUsuarios() {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el perfil de un usuario.
     * @return bool
     */
    public function actualizarPerfil($id_usuario, $nombre, $correo, $contraseña = null) {
        if ($contraseña) {
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ? WHERE id_usuario = ?");
            return $stmt->execute([$nombre, $correo, $hash, $id_usuario]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuario = ?");
            return $stmt->execute([$nombre, $correo, $id_usuario]);
        }
    }

    /**
     * Cambia el rol de un usuario.
     * @return bool
     */
    public function cambiarRol($id_usuario, $nuevo_rol) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?");
        return $stmt->execute([$nuevo_rol, $id_usuario]);
    }

    /**
     * Elimina un usuario.
     * @return bool
     */
    public function eliminarUsuario($id_usuario) {
        // Considerar el manejo de dependencias (juegos, transacciones)
        // Por ahora, se asume que las restricciones de la BD lo manejan
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            return $stmt->execute([$id_usuario]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // --- Métodos para Juegos ---

    /**
     * Obtiene todos los juegos.
     * @return array
     */
    public function getJuegos() {
        $stmt = $this->pdo->prepare("SELECT * FROM juegos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un juego por su ID.
     * @param int $id_juego
     * @return mixed
     */
    public function getJuegoPorId($id_juego) {
        $stmt = $this->pdo->prepare("SELECT * FROM juegos WHERE id_juego = ?");
        $stmt->execute([$id_juego]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los juegos de un vendedor específico.
     * @param int $id_vendedor
     * @return array
     */
    public function getJuegosPorVendedor($id_vendedor) {
        $stmt = $this->pdo->prepare("SELECT * FROM juegos WHERE id_vendedor = ?");
        $stmt->execute([$id_vendedor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los juegos con el nombre del vendedor.
     * @return array
     */
    public function getJuegosConVendedor() {
        $stmt = $this->pdo->prepare("
            SELECT j.*, u.nombre as vendedor_nombre
            FROM juegos j
            JOIN usuarios u ON j.id_vendedor = u.id_usuario
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los géneros únicos de juegos.
     * @return array
     */
    public function getGeneros() {
        $stmt = $this->pdo->prepare("SELECT DISTINCT genero FROM juegos ORDER BY genero ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Crea un nuevo juego.
     * @return bool
     */
    public function crearJuego($titulo, $genero, $precio, $stock, $id_vendedor, $rating, $imagen) {
        $stmt = $this->pdo->prepare("INSERT INTO juegos (titulo, genero, precio, stock, id_vendedor, rating, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$titulo, $genero, $precio, $stock, $id_vendedor, $rating, $imagen]);
    }

    /**
     * Actualiza un juego existente.
     * @return bool
     */
    public function actualizarJuego($id_juego, $titulo, $genero, $precio, $stock) {
        $stmt = $this->pdo->prepare("UPDATE juegos SET titulo = ?, genero = ?, precio = ?, stock = ? WHERE id_juego = ?");
        return $stmt->execute([$titulo, $genero, $precio, $stock, $id_juego]);
    }

    /**
     * Elimina un juego por su ID.
     * @param int $id_juego
     * @return bool
     */
    public function eliminarJuego($id_juego) {
        $stmt = $this->pdo->prepare("DELETE FROM juegos WHERE id_juego = ?");
        return $stmt->execute([$id_juego]);
    }


    // --- Métodos para Transacciones ---

    /**
     * Procesa una compra, creando la transacción y su detalle.
     * @param int $id_usuario
     * @param float $total
     * @param array $carrito
     * @return bool
     */
    public function procesarCompra($id_usuario, $total, $carrito) {
        try {
            $this->pdo->beginTransaction();

            // 1. Crear la transacción
            $stmt = $this->pdo->prepare("INSERT INTO transacciones (id_usuario, total) VALUES (?, ?)");
            $stmt->execute([$id_usuario, $total]);
            $id_transaccion = $this->pdo->lastInsertId();

            // 2. Insertar detalles y actualizar stock
            $stmt_detalle = $this->pdo->prepare("INSERT INTO detalle_transacciones (id_transaccion, id_juego, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $stmt_stock = $this->pdo->prepare("UPDATE juegos SET stock = stock - ? WHERE id_juego = ?");

            foreach ($carrito as $item) {
                $stmt_detalle->execute([$id_transaccion, $item['id_juego'], $item['cantidad'], $item['precio']]);
                $stmt_stock->execute([$item['cantidad'], $item['id_juego']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // Considerar registrar el error $e->getMessage()
            return false;
        }
    }

    /**
     * Obtiene las transacciones de un usuario.
     * @param int $id_usuario
     * @return array
     */
    public function getTransaccionesPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM transacciones WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los detalles de una transacción.
     * @param int $id_transaccion
     * @return array
     */
    public function getDetallesTransaccion($id_transaccion) {
        $stmt = $this->pdo->prepare("
            SELECT d.*, j.titulo
            FROM detalle_transacciones d
            JOIN juegos j ON d.id_juego = j.id_juego
            WHERE d.id_transaccion = ?
        ");
        $stmt->execute([$id_transaccion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     /**
     * Obtiene todas las transacciones (para admin).
     * @return array
     */
    public function getTodasLasTransacciones() {
        $stmt = $this->pdo->prepare("
            SELECT t.*, u.nombre as nombre_usuario
            FROM transacciones t
            JOIN usuarios u ON t.id_usuario = u.id_usuario
            ORDER BY t.fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas para el dashboard del admin.
     * @return array
     */
    public function getEstadisticasAdmin() {
        $stats = [];

        // Total de ventas
        $stats['total_ventas'] = $this->pdo->query("SELECT SUM(total) FROM transacciones")->fetchColumn();

        // Total de juegos
        $stats['total_juegos'] = $this->pdo->query("SELECT COUNT(*) FROM juegos")->fetchColumn();

        // Total de usuarios
        $stats['total_usuarios'] = $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

        // Ventas de hoy
        $stats['ventas_hoy'] = $this->pdo->query("SELECT SUM(total) FROM transacciones WHERE DATE(fecha) = CURDATE()")->fetchColumn();

        // Últimas 5 compras
        $stats['ultimas_compras'] = $this->pdo->query("
            SELECT t.*, u.nombre as nombre_usuario
            FROM transacciones t
            JOIN usuarios u ON t.id_usuario = u.id_usuario
            ORDER BY t.fecha DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Obtiene estadísticas de compras para un usuario.
     * @param int $id_usuario
     * @return array
     */
    public function getEstadisticasUsuario($id_usuario) {
        $stats = [];

        $stmt_compras = $this->pdo->prepare("SELECT COUNT(*), SUM(total) FROM transacciones WHERE id_usuario = ?");
        $stmt_compras->execute([$id_usuario]);
        list($stats['total_compras'], $stats['dinero_gastado']) = $stmt_compras->fetch(PDO::FETCH_NUM);

        return $stats;
    }

    // --- Métodos para Reportes ---

    /**
     * Obtiene el total de ventas por vendedor.
     * @return array
     */
    public function getVentasPorVendedor() {
        $stmt = $this->pdo->query("
            SELECT u.nombre as vendedor, SUM(dt.cantidad * dt.precio_unitario) as total_vendido
            FROM detalle_transacciones dt
            JOIN juegos j ON dt.id_juego = j.id_juego
            JOIN usuarios u ON j.id_vendedor = u.id_usuario
            GROUP BY u.nombre
            ORDER BY total_vendido DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los juegos más vendidos.
     * @param int $limit
     * @return array
     */
    public function getJuegosMasVendidos($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT j.titulo, SUM(dt.cantidad) as unidades_vendidas
            FROM detalle_transacciones dt
            JOIN juegos j ON dt.id_juego = j.id_juego
            GROUP BY j.titulo
            ORDER BY unidades_vendidas DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
