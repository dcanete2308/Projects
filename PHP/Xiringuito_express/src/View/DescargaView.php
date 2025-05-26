<?php
namespace Xiringuito\View;

class DescargaView
{

    public static function show($datos = array(), $sesionIniciada = false)
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
                <h1 class="pixel-font">{$datos['titulo']}</h1>
                <p class="pDes">{$datos['descripcion']}</p>
            </section>
            
            <section class="download-section">
                <h2>{$datos['titulo']}</h2>
                <p>{$datos['descripcion']}</p>
                
                <div class="download-container">
                    <div class="download-item">
                        <div class="download-icon linux-icon"></div>
                        <div class="download-info">
                            <h3>{$datos['version_linux_titulo']}</h3>
                            <p>{$datos['version_linux_descripcion']}</p>
                            <a href="./downloads/XiringuitoExpress.zip" download class="download-button">{$datos['boton_descarga_linux']}</a>
                        </div>
                    </div>
                    
                    <div class="download-item">
                        <div class="download-icon windows-icon"></div>
                        <div class="download-info">
                            <h3>{$datos['version_windows_titulo']}</h3>
                            <p>{$datos['version_windows_descripcion']}</p>
                            <a href="./downloads/XiringuitoExpress.exe" download class="download-button">{$datos['boton_descarga_windows']}</a>
                        </div>
                    </div>
                    
                    <div class="download-item">
                        <div class="download-icon android-icon"></div>
                        <div class="download-info">
                            <h3>{$datos['version_android_titulo']}</h3>
                            <p>{$datos['version_android_descripcion']}</p>
                            <a href="./downloads/XiringuitoLite.apk" download class="download-button">{$datos['boton_descarga_android']}</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="download-section">
                <h2>{$datos['requisitos_titulo']}</h2>
                <div class="download-container">
                    <div class="download-item">
                        <div class="download-info">
                            <h3>{$datos['version_linux_titulo']}</h3>
                            <ul style="list-style-position: inside; padding-left: 1rem;">
        HTML;

        foreach (explode(',', $datos['requisitos_linux']) as $req) {
            echo "<li>" . trim($req) . "</li>";
        }

        echo <<<HTML
                            </ul>
                        </div>
                    </div>
                    
                    <div class="download-item">
                        <div class="download-info">
                            <h3>{$datos['version_windows_titulo']}</h3>
                            <ul style="list-style-position: inside; padding-left: 1rem;">
        HTML;

        foreach (explode(',', $datos['requisitos_windows']) as $req) {
            echo "<li>" . trim($req) . "</li>";
        }

        echo <<<HTML
                            </ul>
                        </div>
                    </div>
                    
                    <div class="download-item">
                        <div class="download-info">
                            <h3>{$datos['version_android_titulo']}</h3>
                            <ul style="list-style-position: inside; padding-left: 1rem;">
        HTML;

        foreach (explode(',', $datos['requisitos_android']) as $req) {
            echo "<li>" . trim($req) . "</li>";
        }

        echo <<<HTML
                            </ul>
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

        echo <<<HTML
        <script>
            document.querySelectorAll('.download-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const platform = this.textContent.includes('Linux') ? 'Linux' :
                                     this.textContent.includes('Windows') ? 'Windows' : 'Android';
                    alert(`Download started for Xiringuito Express \${platform} version. Your file will be downloaded soon.`);
                    window.location.href = this.href;
                });
            });
        </script>
        HTML;

        echo '</body></html>';
    }
}
