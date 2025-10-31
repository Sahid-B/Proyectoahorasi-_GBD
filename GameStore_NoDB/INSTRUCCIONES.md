# Instrucciones de Configuración y Prueba - GameStore con MySQL

Esta guía te ayudará a configurar el proyecto GameStore para que funcione con una base de datos MySQL en un entorno XAMPP.

## Paso 1: Configuración de la Base de Datos

1.  **Abre phpMyAdmin**:
    *   Inicia XAMPP y asegúrate de que los módulos de Apache y MySQL estén en funcionamiento.
    *   Abre tu navegador web y ve a `http://localhost/phpmyadmin`.

2.  **Crea la Base de Datos**:
    *   Haz clic en la pestaña **"Bases de datos"**.
    *   En el campo "Crear base de datos", introduce `gamestore_db`.
    *   Selecciona un cotejamiento como `utf8mb4_general_ci` y haz clic en **"Crear"**.

3.  **Importa la Estructura y los Datos**:
    *   Selecciona la base de datos `gamestore_db` que acabas de crear en la lista de la izquierda.
    *   Ve a la pestaña **"Importar"**.
    *   Haz clic en **"Seleccionar archivo"** y busca el archivo `database.sql` que se encuentra en la raíz del proyecto (`GameStore_NoDB/database.sql`).
    *   Deja las demás opciones con sus valores predeterminados y haz clic en el botón **"Importar"** en la parte inferior de la página.
    *   Si todo va bien, verás un mensaje de éxito y las tablas (`usuarios`, `juegos`, etc.) aparecerán en la estructura de la base de datos.

## Paso 2: Configuración del Archivo de Conexión

1.  **Verifica la Configuración de Conexión**:
    *   Abre el archivo `GameStore_NoDB/Modelo/conexion.php`.
    *   Asegúrate de que los detalles de la conexión coincidan con tu configuración de XAMPP. Por defecto, debería ser:
        ```php
        $servidor = "localhost";
        $nombre_bd = "gamestore_db";
        $usuario = "root";
        $contraseña = "";
        $puerto = 3307; // Asegúrate de que este sea el puerto correcto para tu MySQL
        ```
    *   **Importante**: Si tu MySQL usa un puerto diferente a `3307`, cámbialo en este archivo.

## Paso 3: Probar la Funcionalidad

Ahora que la base de datos está configurada y la aplicación está conectada, puedes probar las diferentes funcionalidades.

1.  **Registro y Login**:
    *   Ve a `http://localhost/GameStore_NoDB/Vista/registro.php` para crear una nueva cuenta de cliente o vendedor.
    *   Intenta registrarte con un correo que ya exista para verificar la validación.
    *   Ve a `http://localhost/GameStore_NoDB/Vista/login.php` y prueba a iniciar sesión con los siguientes usuarios predeterminados:
        *   **Admin**: `admin@gmail.com` / `admin123!`
        *   **Vendedor**: `vendedor@gamestore.com` / `vendedor`
        *   **Cliente**: `cliente@gamestore.com` / `cliente`

2.  **Catálogo y Carrito de Compras**:
    *   Como cliente (o sin iniciar sesión), navega al catálogo en `http://localhost/GameStore_NoDB/Vista/catalogo.php`.
    *   Usa los filtros de búsqueda y género.
    *   Añade varios juegos al carrito desde el catálogo o la página de inicio.
    *   Ve al carrito (`http://localhost/GameStore_NoDB/Vista/carrito.php`) para actualizar cantidades o eliminar productos.

3.  **Proceso de Compra**:
    *   Desde el carrito, procede al checkout.
    *   Completa el proceso de compra.
    *   Una vez finalizada la compra, serás redirigido a "Mis Compras", donde deberías ver tu nuevo pedido.
    *   Verifica en phpMyAdmin que se haya creado una nueva fila en la tabla `transacciones` y las correspondientes en `detalle_transacciones`.
    *   Comprueba también que el stock de los juegos comprados ha disminuido en la tabla `juegos`.

4.  **Panel de Vendedor**:
    *   Inicia sesión como **Vendedor**.
    *   Ve a `http://localhost/GameStore_NoDB/Vista/panel.php`.
    *   Aquí deberías ver solo los juegos que pertenecen a ese vendedor.
    *   Prueba a **añadir**, **editar** y **eliminar** un juego. Intenta eliminar un juego que ya forma parte de una transacción para verificar que la base de datos lo impide.

5.  **Panel de Administración (Admin)**:
    *   Inicia sesión como **Admin**.
    *   Ve a `http://localhost/GameStore_NoDB/Vista/admin_dashboard.php` para ver las estadísticas generales.
    *   Ve a **"Gestionar Usuarios"** (`admin_usuarios.php`) para ver, buscar y eliminar usuarios, o cambiarles el rol. Intenta no eliminarte a ti mismo.
    *   Ve a **"Gestionar Juegos"** (`panel.php`), donde el admin puede ver y gestionar todos los juegos de todos los vendedores.
    *   Accede a los **"Reportes"** para ver las ventas por vendedor y los juegos más vendidos.

Si todas estas funcionalidades se comportan como se espera, la migración a la base de datos ha sido un éxito.
