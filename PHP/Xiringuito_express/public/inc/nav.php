<header>
    <div class="logo">XIRINGUITO EXPRESS</div>
    <nav>
        <ul>
            <li><a href="index.php?/Home/show">Inicio</a></li>
            <li><a href="index.php?/Noticia/show">Noticias</a></li>
            <li><a href="index.php?/Descarga/show">Descarga</a></li>
            <li><a href="index.php?/XiForo/show">XiForo</a></li>
            <li><a href="index.php?/Personatge/show">Personajes</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <form method="post" action="index.php?/Login/logout" style="display:inline;">
                        <button class="signout-button" type="submit">Cerrar sesión</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="index.php?/Login/show">Inicio sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>