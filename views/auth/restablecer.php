<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        
        <form class="formulario" method="POST" action="/restablecer">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>

            <input type="submit" class="boton" value="Guardar password">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes cuenta? Obten una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>