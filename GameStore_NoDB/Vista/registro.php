<?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="text-center mb-4">Crear Cuenta</h2>
                 <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">El correo ya está en uso.</div>
                <?php endif; ?>

                <form action="../Controlador/registro.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    </div>
                    <div class="mb-4">
                        <label for="rol" class="form-label">Registrarse como</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="cliente">Cliente</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>

                <div class="text-center mt-4">
                    <p>¿Ya tienes cuenta? <a href="login.php" class="text-neon">Inicia sesión</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>.text-neon { color: var(--neon-accent); text-decoration: none; } .text-neon:hover{ text-decoration: underline; }</style>
