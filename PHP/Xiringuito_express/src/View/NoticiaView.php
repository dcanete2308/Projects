<?php
namespace Xiringuito\View;

class NoticiaView {
    public static function show($noticies = array(), $sesionIniciada = false, $esAdmin = true, $textos = array(), $idiomes = array(), $errores = array()) {
        
        echo <<<HTML
            <!DOCTYPE html>
            <html lang="en">
        HTML;
        
        include __ROOT__.'/public/inc/head.php';
        echo <<<HTML
                <script>
                    function openModal() {
                        document.getElementById("personatge-modal").style.display = "block";
                    }
                    function closeModal() {
                        document.getElementById("personatge-modal").style.display = "none";
                    }
                    
                    function showNewsModal(img, titol, data, descripcio) {
                        document.getElementById('modal-img').src = img;
                        document.getElementById('modal-title').textContent = titol;
                        document.getElementById('modal-date').textContent = data;
                        document.getElementById('modal-desc').textContent = descripcio;
                        document.getElementById("news-modal").style.display = "block";
                    }
                    
                    function closeNewsModal() {
                        document.getElementById("news-modal").style.display = "none";
                    }
                    
                </script>
            HTML;
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
        echo '<div class="container">
            <section class="news-section">';
        if($esAdmin) {
            echo '<div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>'. $textos['form_noticias']['titol'].'</h2>
                    <button onclick="openModal()" class="cta-button">'. $textos['form_noticias']['btn_create'] .'</button>
                 </div>';
        } else {
            echo '<h2>'. $textos['form_noticias']['titol'].'</h2>';
        }
        
        echo <<<HTML
            <div class="news-grid">
        HTML;
        
        if (!empty($noticies)) {
            foreach ($noticies as $n) {
                if ($n !== null) {
                    echo "
           <div class=\"news-card\" onclick=\"showNewsModal('{$n->getImg()}','{$n->getTitol()}',
            '{$n->getData()->format('d/m/Y')}',`" . htmlspecialchars($n->getDescripcio(), ENT_QUOTES) . "`)\">
                <div class=\"news-image\">
                    <img src=\"{$n->getImg()}\"></img>
                </div>
                <div class=\"news-content\">
                    <span class=\"news-date\">{$n->getData()->format('d/m/Y')}</span>
                    <h3>{$n->getTitol()}</h3>
                    <p style=\"text-align: justify;\">{$n->getDescripcio()}</p>
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
        echo ' </div></section></div>';
        
        if ($esAdmin) {
            echo <<<HTML
            <div id="personatge-modal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span onclick="closeModal()" class="close">&times;</span>
            HTML;
            echo ' <h3>'. $textos['form_noticias']['titol'] . '</h3>';
            echo '<form action="index.php?/Noticia/add" method="POST" enctype="multipart/form-data">';
            
            echo '<input type="text" name="titol" placeholder="' . $textos['form_noticias']['placeholder_titol'] . '" required>';
            if (isset($errores['titol']))
                echo '<p class="error-message">' . $errores['titol'] . '</p>';
                
                echo '<textarea class="respuestaInput" name="descripcio" placeholder="' . $textos['form_noticias']['placeholder_descripcio'] . '" required></textarea>';
                if (isset($errores['descripcio']))
                    echo '<p class="error-message">' . $errores['descripcio'] . '</p>';
                    
                    echo '<select name="idioma_id">';
                    echo '<option value="">' . $textos['form_noticias']['seleccionar_idioma'] . '</option>';
                    foreach ($idiomes as $idioma) {
                        echo '<option value="' . $idioma->getId() . '">' . $idioma->getNom() . '</option>';
                    }
                    echo '</select>';
                    if (isset($errores['idioma_id']))
                        echo '<p class="error-message">' . $errores['idioma_id'] . '</p>';
                        
                        echo '<input type="file" name="img" placeholder="' . $textos['form_noticias']['placeholder_img'] . '" required>';
                        
                        echo '<button type="submit" class="cta-button">' . $textos['form_noticias']['btn_publicar'] . '</button>';
                        if (isset($errores['general']))
                            echo '<p class="error-message" style="margin-top: 10px;">' . $errores['general'] . '</p>';
                            
                            echo <<<HTML
                    </form>
                </div>
            </div>
            HTML;
        }
        echo <<<HTML
            <div id="news-modal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span onclick="closeNewsModal()" class="close">&times;</span>
            HTML;
        
        echo '<div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="news-modal-text" style="flex: 1;">
                    <h3 id="modal-title" style="margin-top: 0;"></h3>
                    <span id="modal-date" class="news-date" style="font-size: 0.9em; color: gray;"></span>
                    <p id="modal-desc" style="text-align: justify; margin-top: 10px;"></p>
                </div>
            
                <div class="news-modal-image" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                    <img id="modal-img" src="" alt="Imatge notícia" style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;" />
                </div>
            </div>
        </div>
        ';
        
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
        echo'
            </body>
            </html>';
    }
}