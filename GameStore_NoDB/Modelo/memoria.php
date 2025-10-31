<?php
// GameStore_NoDB/Modelo/memoria.php

class Memoria {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['db'])) {
            $_SESSION['db'] = [
                'usuarios' => [],
                'juegos' => [],
                'transacciones' => [],
                'detalle_transacciones' => [],
                'next_user_id' => 1,
                'next_juego_id' => 1,
                'next_transaccion_id' => 1,
                'next_detalle_id' => 1,
            ];

            // Add default users
            $admin_id = self::addUser('Administrador', 'admin@gmail.com', 'admin123!', 'admin');
            $vendedor_id = self::addUser('Vendedor Uno', 'vendedor@gamestore.com', 'vendedor', 'vendedor');
            self::addUser('Cliente Uno', 'cliente@gamestore.com', 'cliente', 'cliente');

            // Add an extensive list of default games
            $games = [
                ['The Witcher 3', 'RPG', 39.99, 50, $vendedor_id, 5, 'imgtw3.jpg'],
                ['Cyberpunk 2077', 'RPG', 49.99, 30, $vendedor_id, 4, 'imgCyberpunk.jpg'],
                ['Red Dead Redemption 2', 'Action', 59.99, 40, $vendedor_id, 5, 'imgrdr2.jpg'],
                ['Hades', 'Roguelike', 24.99, 100, $vendedor_id, 5, 'imghades.jpg'],
                ['Elden Ring', 'Action RPG', 59.99, 60, $vendedor_id, 5, 'imgeldenring.jpg'],
                ['Baldur\'s Gate 3', 'RPG', 59.99, 70, $vendedor_id, 5, 'imgbg3.jpg'],
                ['GTA V', 'Action', 29.99, 200, $vendedor_id, 5, 'imgGTA5.jpg'],
                ['Zelda: Breath of the Wild', 'Action-Adventure', 59.99, 80, $admin_id, 5, 'imgzelda.jpg'],
                ['Stardew Valley', 'Simulation', 14.99, 150, $vendedor_id, 5, 'imgstardew.jpg'],
                ['Hollow Knight', 'Metroidvania', 14.99, 120, $vendedor_id, 5, 'imghollow.jpg'],
                ['God of War (2018)', 'Action-Adventure', 39.99, 90, $admin_id, 5, 'imggow.jpg'],
                ['Minecraft', 'Sandbox', 26.95, 500, $vendedor_id, 5, 'imgminecraft.jpg'],
                ['Among Us', 'Social', 4.99, 300, $vendedor_id, 4, 'imgamongus.jpg'],
                ['Fortnite', 'Battle Royale', 0.00, 1000, $admin_id, 4, 'imgfortnite.jpg'],
                ['Valorant', 'FPS', 0.00, 1000, $admin_id, 4, 'imgvalorant.jpg'],
                ['League of Legends', 'MOBA', 0.00, 1000, $admin_id, 3, 'imglol.jpg'],
                ['Overwatch 2', 'FPS', 0.00, 1000, $vendedor_id, 4, 'imgow2.jpg'],
                ['Apex Legends', 'Battle Royale', 0.00, 1000, $vendedor_id, 4, 'imgapex.jpg'],
                ['DOOM Eternal', 'FPS', 39.99, 70, $admin_id, 5, 'imgdoom.jpg'],
                ['Final Fantasy VII Remake', 'RPG', 59.99, 60, $vendedor_id, 4, 'imgff7r.jpg'],
                ['Persona 5 Royal', 'RPG', 59.99, 50, $vendedor_id, 5, 'imgp5r.jpg'],
                ['Ghost of Tsushima', 'Action-Adventure', 49.99, 80, $admin_id, 5, 'imggot.jpg'],
                ['Marvel\'s Spider-Man', 'Action-Adventure', 39.99, 100, $vendedor_id, 5, 'imgspiderman.jpg'],
                ['Bloodborne', 'Action RPG', 19.99, 60, $admin_id, 5, 'imgbloodborne.jpg'],
                ['Sekiro: Shadows Die Twice', 'Action-Adventure', 59.99, 50, $vendedor_id, 5, 'imgsekiro.jpg'],
                ['Dark Souls III', 'Action RPG', 39.99, 70, $admin_id, 5, 'imgds3.jpg'],
                ['Celeste', 'Platformer', 19.99, 90, $vendedor_id, 5, 'imgceleste.jpg'],
                ['Subnautica', 'Survival', 29.99, 80, $vendedor_id, 5, 'imgsubnautica.jpg'],
                ['Skyrim', 'RPG', 39.99, 250, $admin_id, 5, 'imgskyrim.jpg'],
                ['Portal 2', 'Puzzle', 9.99, 300, $vendedor_id, 5, 'imgportal2.jpg'],
            ];

            foreach ($games as $game) {
                self::addJuego($game[0], $game[1], $game[2], $game[3], $game[4], $game[5], $game[6]);
            }
        }
    }

    private static function addUser($nombre, $correo, $password, $rol) {
        $id = $_SESSION['db']['next_user_id']++;
        $_SESSION['db']['usuarios'][$id] = [
            'id_usuario' => $id,
            'nombre' => $nombre,
            'correo' => $correo,
            'contraseña' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
        ];
        return $id;
    }

    private static function addJuego($titulo, $genero, $precio, $stock, $vendedor_id, $rating, $image_name) {
        $id = $_SESSION['db']['next_juego_id']++;
        $_SESSION['db']['juegos'][$id] = [
            'id_juego' => $id,
            'titulo' => $titulo,
            'genero' => $genero,
            'precio' => $precio,
            'stock' => $stock,
            'id_vendedor' => $vendedor_id,
            'rating' => $rating,
            'image' => "https://placehold.co/400x550/1a1a2e/e94560?text=" . urlencode($titulo)
        ];
        return $id;
    }
}

// Initialize the data store on first include
Memoria::init();
?>
