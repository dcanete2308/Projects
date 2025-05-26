<?php
namespace Xiringuito\View;

class PersonatgeView
{
    public static function show($personatges = array(), $mapas = array(), $sesionIniciada = false, $esAdmin = false, $idiomes = array(), $textos = array(), $errores = array(), $erroresMapa = array())
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        HTML;
        
        include __ROOT__ . '/public/inc/head.php';
        
        echo <<<HTML
        <body>
        HTML;
        
        $lang = $_COOKIE['lang'] ?? 'ca';
        $navFile = match ($lang) {
            'es' => 'nav.php',
            'en' => 'navEn.php',
            default => 'navCa.php',
        };
        include __ROOT__ . 'public/inc/' . $navFile;
        
        echo '<div class="container">';
        echo '<section class="maps-section">';
        echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
        echo '<h2>' . $textos['mapas']['titulo'] . '</h2>';
        if ($esAdmin) {
            echo '<button onclick="openMapaModal()" class="cta-button">' . $textos['mapas']['boton_añadir'] . '</button>';
        }
        echo '</div><div class="maps-grid">';
        
        if (!empty($mapas)) {
            foreach ($mapas as $mapa) {
                if ($mapa->getNom() !== 'Global') {
                    $onclick = "showMapModal(" .
                        "'" . addslashes($mapa->getImg()) . "'," .
                        "'" . addslashes($mapa->getNom()) . "'," .
                        "'" . addslashes($mapa->getDescripcio()) . "'" .
                        ")";
                    echo '
                        <div class="map-item" onclick="' . $onclick . '" style="background-image: url(\'' . $mapa->getImg() . '\'); cursor:pointer;">
                            <div class="map-name">' . $mapa->getNom() . '</div>
                        </div>';
                }
            }
        } else {
            echo '<p style="text-align: center;">No hi ha mapes</p>';
        }
        
        echo '</div></section><section class="characters-card"><div class="characters-header">';
        echo "<h2>{$textos['personaje']['titulo']}</h2>";
        
        if ($esAdmin) {
            echo '<button onclick="openModal()" class="cta-button">' . $textos['personaje']['modal_titulo'] . '</button>';
        }
        
        echo '</div><div class="character-card-items">';
        
        if (!empty($personatges)) {
            foreach ($personatges as $p) {
                $img = htmlspecialchars($p->getImg(), ENT_QUOTES);
                $nom = htmlspecialchars($p->getNom(), ENT_QUOTES);
                $descripcio = htmlspecialchars($p->getDescripcio(), ENT_QUOTES);
                $vida = htmlspecialchars($p->getVida(), ENT_QUOTES);
                $dany = htmlspecialchars($p->getDany(), ENT_QUOTES);
                $nivellNom = htmlspecialchars($p->getNivell()->getNom(), ENT_QUOTES);
                $onclick = "showCharactersModal(" .
                    "'" . addslashes($img) . "'," .
                    "'" . addslashes($nom) . "'," .
                    "'" . addslashes($descripcio) . "'," .
                    "'" . addslashes($vida) . "'," .
                    "'" . addslashes($dany) . "'," .
                    "'" . addslashes($nivellNom) . "'" .
                    ")";
                
                echo "<div class=\"news-card\" onclick=\"$onclick\">";
                echo "
                    <div class=\"chardiv\">
                        <div class=\"character-card-image\">
                            <img src=\"{$img}\" alt=\"{$nom}\">
                        </div>
                        <h3>{$nom}</h3>
                        <p>{$descripcio}</p>
                    </div>
                    <div class=\"character-card-stats\">
                        <p><strong>{$textos['personaje']['placeholder_vida']}:</strong> {$vida}</p>
                        <p><strong>{$textos['personaje']['placeholder_danyo']}:</strong> {$dany}</p>
                        <p><strong>{$textos['personaje']['placeholder_local']}:</strong> {$nivellNom}</p>
                    </div>
                </div>";
            }
        } else {
            echo '<p style="text-align: center;">No hi ha personatges</p>';
        }
        
        echo '</div></section>';
        
        if ($esAdmin) {
            echo <<<HTML
            <div id="personatge-modal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span onclick="closeModal()" class="close">&times;</span>
                    <h3>{$textos['personaje']['modal_titulo']}</h3>
                    <form action="index.php?/Personatge/add" method="POST" enctype="multipart/form-data">
            HTML;
            
            echo '<input type="text" name="nom" placeholder="' . $textos['personaje']['placeholder_nombre'] . '" required>';
            if (isset($errores['nom'])) echo '<p class="error-message">' . $errores['nom'] . '</p>';
            
            echo '<textarea class="respuestaInput" name="descripcio" placeholder="' . $textos['personaje']['placeholder_descripcion'] . '" required></textarea>';
            if (isset($errores['descripcio'])) echo '<p class="error-message">' . $errores['descripcio'] . '</p>';
            
            echo '<input type="number" name="vida" placeholder="' . $textos['personaje']['placeholder_vida'] . '" required>';
            if (isset($errores['vida'])) echo '<p class="error-message">' . $errores['vida'] . '</p>';
            
            echo '<input type="number" name="dany" placeholder="' . $textos['personaje']['placeholder_danyo'] . '" required>';
            if (isset($errores['dany'])) echo '<p class="error-message">' . $errores['dany'] . '</p>';
            
            echo '<select name="idioma_id" required><option value="">' . $textos['personaje']['seleccionar_idioma'] . '</option>';
            foreach ($idiomes as $idioma) {
                echo '<option value="' . $idioma->getId() . '">' . $idioma->getNom() . '</option>';
            }
            echo '</select>';
            if (isset($errores['idioma_id'])) echo '<p class="error-message">' . $errores['idioma_id'] . '</p>';
            
            echo '<input type="file" name="img" required>';
            
            echo '<select name="nivell_id" required><option value="">' . $textos['personaje']['seleccionar_nivel'] . '</option>';
            foreach ($mapas as $mapa) {
                if ($mapa->getNom() !== 'Global') {
                    echo '<option value="' . $mapa->getId() . '">' . $mapa->getNom() . '</option>';
                }
            }
            echo '</select>';
            if (isset($errores['nivell_id'])) echo '<p class="error-message">' . $errores['nivell_id'] . '</p>';
            
            echo '<button type="submit" class="cta-button">' . $textos['personaje']['boton_crear'] . '</button>';
            if (isset($errores['general'])) echo '<p class="error-message" style="margin-top: 10px;">' . $errores['general'] . '</p>';
            
            echo '</form></div></div>';
            
            echo '<div id="mapa-modal" class="modal" style="display:none;"><div class="modal-content">';
            echo '<span onclick="closeMapaModal()" class="close">&times;</span>';
            echo '<h3>' . $textos['mapa']['modal_titulo'] . '</h3>';
            echo '<form action="index.php?/Personatge/addNivell" method="POST" enctype="multipart/form-data">';
            echo '<input type="text" name="nom" placeholder="' . $textos['mapa']['placeholder_nombre'] . '" required>';
            if (isset($erroresMapa['nom'])) echo '<p class="error-message">' . $erroresMapa['nom'] . '</p>';
            
            echo '<input type="text" name="desc" placeholder="' . $textos['personaje']['placeholder_descripcion'] . '" required>';
            if (isset($erroresMapa['desc'])) echo '<p class="error-message">' . $erroresMapa['desc'] . '</p>';
            
            echo '<select name="idioma_id" required><option value="">' . $textos['mapa']['seleccionar_idioma'] . '</option>';
            foreach ($idiomes as $idioma) {
                echo '<option value="' . $idioma->getId() . '">' . $idioma->getNom() . '</option>';
            }
            echo '</select>';
            if (isset($erroresMapa['idioma_id'])) echo '<p class="error-message">' . $erroresMapa['idioma_id'] . '</p>';
            
            echo '<input type="file" name="img" required>';
            if (isset($erroresMapa['img'])) echo '<p class="error-message">' . $erroresMapa['img'] . '</p>';
            
            echo '<button type="submit" class="cta-button">' . $textos['mapa']['boton_crear'] . '</button>';
            if (isset($erroresMapa['general'])) echo '<p class="error-message" style="margin-top: 10px;">' . $erroresMapa['general'] . '</p>';
            
            echo '</form></div></div>';
        }
        
        echo <<<HTML
        <div id="character-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span onclick="closeCharacterModal()" class="close">&times;</span>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="character-modal-text" style="flex: 1;">
                        <h3 id="modal-nom-c" style="margin-top: 0;"></h3>
                        <p id="modal-desc-c" style="text-align: justify; margin-top: 10px;"></p>
                        <div class="character-card-stats">
                            <p><strong>{$textos['personaje']['placeholder_vida']}:</strong> <span id="modal-vida-c"></span></p>
                            <p><strong>{$textos['personaje']['placeholder_danyo']}:</strong> <span id="modal-dany-c"></span></p>
                            <p><strong>{$textos['personaje']['placeholder_local']}:</strong> <span id="modal-lvl-c"></span></p>
                        </div>
                    </div>
                    <div class="news-modal-image" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                        <img id="modal-img-c" src="" alt="Imatge notícia" style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;" />
                    </div>
                </div>
            </div>
        </div>
        <div id="map-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span onclick="closeMapModal()" class="close">&times;</span>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="character-modal-text" style="flex: 1;">
                        <h3 id="modal-nom-mapa" style="margin-top: 0;"></h3>
                        <p id="modal-desc-mapa" style="text-align: justify; margin-top: 10px;"></p>
                    </div>
                    <div class="news-modal-image" style="flex: 1; display: flex; align-items: center; justify-content: center;">
                        <img id="modal-img-mapa" src="" alt="Imatge mapa" style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;" />
                    </div>
                </div>
            </div>
        </div>
        HTML;
        
        echo "</div>";
        $footerFile = match ($lang) {
            'es' => 'footer.php',
            'en' => 'footerEn.php',
            default => 'footerCa.php',
        };
        include __ROOT__ . '/public/inc/' . $footerFile;
        
        echo "
            <script>
                function openModal() {
                    document.getElementById('personatge-modal').style.display = 'block';
                }
            
                function closeModal() {
                    document.getElementById('personatge-modal').style.display = 'none';
                }
            
                function openMapaModal() {
                    document.getElementById('mapa-modal').style.display = 'block';
                }
            
                function closeMapaModal() {
                    document.getElementById('mapa-modal').style.display = 'none';
                }
            
                function showCharactersModal(img, nom, descripcio, vida, dany, nivell) {
                    document.getElementById('modal-img-c').src = img;
                    document.getElementById('modal-nom-c').textContent = nom;
                    document.getElementById('modal-desc-c').textContent = descripcio;
                    document.getElementById('modal-vida-c').textContent = vida;
                    document.getElementById('modal-dany-c').textContent = dany;
                    document.getElementById('modal-lvl-c').textContent = nivell;
                    document.getElementById('character-modal').style.display = 'block';
                }
            
                function closeCharacterModal() {
                    document.getElementById('character-modal').style.display = 'none';
                }
            
                function showMapModal(img, nom, descripcio) {
                    document.getElementById('modal-img-mapa').src = img;
                    document.getElementById('modal-nom-mapa').textContent = nom;
                    document.getElementById('modal-desc-mapa').textContent = descripcio;
                    document.getElementById('map-modal').style.display = 'block';
                }
            
                function closeMapModal() {
                    document.getElementById('map-modal').style.display = 'none';
                }
            
                window.onload = function () {
                    const errorsPersonatge = " . (!empty($errores) ? 'true' : 'false') . ";
                    const errorsMapa = " . (!empty($erroresMapa) ? 'true' : 'false') . ";
                        
                    if (errorsPersonatge) {
                        openModal();
                    }
                        
                    if (errorsMapa) {
                        openMapaModal();
                    }
                };
                        
                window.onclick = function(event) {
                    const modals = [
                        'personatge-modal',
                        'mapa-modal',
                        'character-modal',
                        'map-modal'
                    ];
                    modals.forEach(function(id) {
                        const modal = document.getElementById(id);
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                };
            </script>
            </body>
            </html>
            ";
        
        
    }
}