<?php
namespace Xiringuito\View;

class HomeView {
    
    public static function show($datos = array(), $sesionIniciada = false) {
        
        echo <<<HTML
            <!DOCTYPE html>
            <html lang="en">
        HTML;
        
        include __ROOT__.'/public/inc/head.php';
        
        echo <<<HTML
            <body>
        HTML;
        $lang = $_COOKIE['lang'] ?? 'ca';
        
        switch ($lang) {
            case 'es':
                $navFile = 'nav.php';
                break;
            case 'en':
                $navFile = 'navEn.php';
                break;
            default:
                $navFile = 'navCa.php';
                break;
        }
        
        include __ROOT__ . 'public/inc/' . $navFile;
        
        echo <<<HTML
        <div class="container">
            <section class="hero">
                <h1 class="pixel-font">{$datos['heroTitulo']->getContenido()}</h1>
                <p style="color: black;">{$datos['heroContenido']->getContenido()}</p>
                <a href="#" class="cta-button">{$datos['gameHistorySubtitulo']->getContenido()}</a>
            </section>
            
            <section class="game-history" style="text-align: justify;">
                <h2>{$datos['download']->getContenido()}</h2>
                <p>{$datos['gameHistoryContenido']->getContenido()}</p>
                <p>{$datos['gameHistoryContenido2']->getContenido()}</p>
                <p>{$datos['gameHistoryContenido3']->getContenido()}</p>
                <p>{$datos['gameHistoryContenido4']->getContenido()}</p>
            </section>
            
            <section class="news-section">
                <h2>{$datos['noticiasSectionTitle']->getContenido()}</h2>
                <div class="news-grid">
        HTML;
        
        if (!empty($datos['noticias'])) {
            foreach ($datos['noticias'] as $n) {
                if ($n !== null) {
                    echo "
            <div class=\"news-card\">
                <div class=\"news-image\">
                    <img src=\"{$n->getImg()}\"></img>
                </div>
                <div class=\"news-content\">
                    <span class=\"news-date\">{$n->getData()->format('d/m/Y')}</span>
                    <h3>{$n->getTitol()}</h3>
                    <p>{$n->getDescripcio()}</p>
                </div>
            </div>
            ";
                } else {
                    echo "<p>No se pudo cargar la noticia.</p>";
                }
            }
        } else {
            echo '<p style="text-align: center;">No hi ha notícies</p>';
        }
        
        echo <<<HTML
                </div>
                    <a href="index.php?/Noticia/show" class="news-button">{$datos['verTodasNoticiasTexto']->getContenido()}</a>
            </section>
            
            <section class="developers">
                <h2>{$datos['developerSectionTitle']->getContenido()}</h2>
                <div class="developer-items">
                    <div class="developer-item">
                        <div class="developer-image" style="background-image: url('./media/perfil1.png');"></div>
                        <div class="developer-info">
                            <h3>{$datos['developerDidac']->getContenido()}</h3>
                            <p style="text-align: justify">{$datos['developerDidacContent']->getContenido()}</p>
                        </div>
                    </div>
                    
                    <div class="developer-item">
                        <div class="developer-image" style="background-image: url('./media/perfil2.png');"></div>
                        <div class="developer-info">
                            <h3>{$datos['developerClaudia']->getContenido()}</h3>
                            <p style="text-align: justify">{$datos['developerClaudiaContent']->getContenido()}</p>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>
HTML;
        
        
        switch ($lang) {
            case 'es':
                $footerFile = 'footer.php';
                break;
            case 'en':
                $footerFile = 'footerEn.php';
                break;
            default:
                $footerFile = 'footerCa.php';
                break;
        }
        include __ROOT__ . '/public/inc/' . $footerFile;
        
        echo '</body></html>';
    }
}