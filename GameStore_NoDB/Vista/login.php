<?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="text-center mb-4">Iniciar Sesión</h2>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Usuario o contraseña incorrectos.</div>
                <?php endif; ?>
                <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
                    <div class="alert alert-success">Registro exitoso. ¡Inicia sesión!</div>
                <?php endif; ?>

                <form action="../Controlador/login.php" method="POST">
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-4">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Acceder</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">¿No tienes cuenta? <a href="registro.php" class="text-neon">Regístrate</a></p>
                    <a href="index.php" class="text-neon">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>.text-neon { color: var(--neon-accent); text-decoration: none; } .text-neon:hover{ text-decoration: underline; }</style>
