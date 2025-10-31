<?php
// GameStore_NoDB/Modelo/conexion.php

class Conexion {
    public static function conectar() {
        $servidor = "localhost";
        $nombre_bd = "gamestore_db";
        $usuario = "root";
        $contraseña = "";
        $puerto = 3307; // Puerto personalizado

        try {
            $dsn = "mysql:host=$servidor;port=$puerto;dbname=$nombre_bd;charset=utf8mb4";
            $pdo = new PDO($dsn, $usuario, $contraseña);

            // Configurar PDO para que lance excepciones en caso de error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            // Manejar errores de conexión
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
