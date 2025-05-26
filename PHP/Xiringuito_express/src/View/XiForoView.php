<?php
namespace Xiringuito\View;

class XiForoView
{
    
    public static function show($sesionIniciada = false, $mensajes = array(), $paginaActual = 1, $totalPaginas = 1, $errores = array(), $oldContenido = '', $textos = array())
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
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
        
        $foroTextos = isset($textos['foro']) ? $textos['foro'] : [];
        
        echo <<<HTML
        <div class="container">
            <section class="forum-header">
                <h1>{$foroTextos['titulo']}</h1>
                <p>{$foroTextos['descripcion']}</p>
            </section>
            
            <section class="topic-container">
                <header>
                    <h1>{$foroTextos['tema']}</h1>
                </header>
                
                <ul class="post-list">
        HTML;
        
        foreach ($mensajes as $mensaje) {
            if ($mensaje->getPadre() === null) {
                self::renderMensaje($mensaje, $sesionIniciada, $foroTextos);
            }
        }
        
        echo <<<HTML
                </ul>
            </section>
        HTML;
        
        if ($sesionIniciada) {
            $errorsHtml = '';
            if (! empty($errores)) {
                $errorsHtml .= '<div class="form-errors" style="color: red; margin-top: 0.5rem;">';
                foreach ($errores as $error) {
                    $errorsHtml .= "<p>$error</p>";
                }
                $errorsHtml .= '</div>';
            }
            
            $safeContenido = is_array($oldContenido) ? '' : htmlspecialchars($oldContenido);
            
            $placeholderRespuesta = $foroTextos['placeholder_respuesta'] ?? 'Escribe tu respuesta aquí...';
            $botonResponder = $foroTextos['boton_responder'] ?? 'Responder';
            
            echo <<<HTML
            <section class="reply-box">
                <h3>{$foroTextos['responder_tema']}</h3>
                <form method="POST" action="index.php?XiForo/post&page=$paginaActual">
                    <textarea name="contenido" placeholder="$placeholderRespuesta" required>$safeContenido</textarea>
                    $errorsHtml
                    <button type="submit" class="cta-button">$botonResponder</button>
                </form>
            </section>
            HTML;
        }
        
        echo '<div class="pagination">';
        if ($paginaActual > 1) {
            $prev = $paginaActual - 1;
            echo "<a href=\"index.php?XiForo/show&page=1\">{$foroTextos['paginacion_primera']}</a>";
            echo "<a href=\"index.php?XiForo/show&page=" . max(1, $paginaActual - 10) . "\">{$foroTextos['paginacion_atras10']}</a>";
            echo "<a href=\"index.php?XiForo/show&page=$prev\">{$foroTextos['paginacion_anterior']}</a>";
        }
        
        echo "<span class=\"pagination-info\">" . str_replace(['{0}', '{1}'], [$paginaActual, $totalPaginas], $foroTextos['paginacion_info']) . "</span>";
        
        if ($paginaActual < $totalPaginas) {
            $next = $paginaActual + 1;
            echo "<a href=\"index.php?XiForo/show&page=$next\">{$foroTextos['paginacion_siguiente']}</a>";
            echo "<a href=\"index.php?XiForo/show&page=" . min($totalPaginas, $paginaActual + 10) . "\">{$foroTextos['paginacion_adelante10']}</a>";
            echo "<a href=\"index.php?XiForo/show&page=$totalPaginas\">{$foroTextos['paginacion_ultima']}</a>";
        }
        echo '</div>';
        
        $modalTitulo = $foroTextos['modal_titulo'] ?? 'Responder al mensaje';
        $botonEnviar = $foroTextos['boton_enviar'] ?? 'Enviar';
        
        echo <<<HTML
        </div>
        
        <div id="respuesta-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span onclick="closeModal()" class="close">&times;</span>
                <h3>$modalTitulo</h3>
                <form method="POST" action="index.php?XiForo/post&page=$paginaActual">
                    <input type="hidden" name="padre_id" id="modal-padre-id" />
                    <textarea name="contenido" placeholder="{$foroTextos['placeholder_respuesta']}" class="respuestaInput" required></textarea>
                    <button type="submit" class="cta-button">$botonEnviar</button>
                </form>
            </div>
        </div>
        
        <script>
            function openModal(padreId) {
                document.getElementById('modal-padre-id').value = padreId;
                document.getElementById('respuesta-modal').style.display = 'block';
            }
            function closeModal() {
                document.getElementById('respuesta-modal').style.display = 'none';
            }
        </script>
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
        </body>
        </html>
        HTML;
    }
    
    private static function renderMensaje($mensaje, bool $sesionIniciada, array $foroTextos)
    {
        $autor = $mensaje->getUsuari();
        $nombre = htmlspecialchars($autor->getNom());
        $rol = htmlspecialchars($autor->getRol()->getNom());
        $fecha = $mensaje->getCreatedAt()->format('d/m/Y H:i');
        $contenido = nl2br(htmlspecialchars($mensaje->getContent()));
        $id = $mensaje->getId();
        
        $publicado = $foroTextos['publicado'] ?? 'Publicado';
        
        echo <<<HTML
            <li class="post-item">
                <div class="post-header">
                    <div class="post-author">
                        <div>
                            <div class="author-name">$nombre</div>
                            <div class="author-role">$rol</div>
                        </div>
                    </div>
                    <div class="post-date">$publicado: $fecha</div>
                </div>
                <div class="post-content">
                    <p>$contenido</p>
                </div>
        HTML;
        
        if ($sesionIniciada) {
            echo <<<HTML
                <div class="post-actions">
                    <button onclick="openModal($id)">{$foroTextos['boton_responder']}</button>
                </div>
            HTML;
        }
        
        $respuestas = $mensaje->getRespuestas();
        if (count($respuestas) > 0) {
            echo '<ul class="respuesta-list">';
            foreach ($respuestas as $respuesta) {
                self::renderMensaje($respuesta, $sesionIniciada, $foroTextos);
            }
            echo '</ul>';
        }
        
        echo '</li>';
    }
}

