<header>
    <div class="logo">XIRINGUITO EXPRESS</div>
    <nav>
        <ul>
            <li><a href="index.php?/Home/show">Inici</a></li>
            <li><a href="index.php?/Noticia/show">Noticies</a></li>
            <li><a href="index.php?/Descarga/show">Descàrrega</a></li>
            <li><a href="index.php?/XiForo/show">XiForum</a></li>
            <li><a href="index.php?/Personatge/show">Personatges</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <form method="post" action="index.php?/Login/logout" style="display:inline;">
                        <button class="signout-button" type="submit">Tancar sessió</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="index.php?/Login/show">Inici sessió</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>