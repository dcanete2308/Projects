<header>
    <div class="logo">XIRINGUITO EXPRESS</div>
    <nav>
        <ul>
            <li><a href="index.php?/Home/show">Home</a></li>
            <li><a href="index.php?/Noticia/show">News</a></li>
            <li><a href="index.php?/Descarga/show">Download</a></li>
            <li><a href="index.php?/XiForo/show">XiForum</a></li>
            <li><a href="index.php?/Personatge/show">Characters</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <form method="post" action="index.php?/Login/logout" style="display:inline;">
                        <button class="signout-button" type="submit">Sign out</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="index.php?/Login/show">Log in</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>