<?php
namespace Xiringuito\View;

class LoginView
{

    public static function show($titulo, $labelEmail, $labelPassword, $buttonText, $registroPregunta, $registroEnlace, string $email = '', array $errors = [], $sesionIniciada = false)
    {
        $emailValue = htmlspecialchars($email);
        $emailError = $errors['email'] ?? '';
        $passwordError = $errors['password'] ?? '';
        $generalError = $errors['general'] ?? '';

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
            
        $emailErrorClass = $emailError ? 'true' : 'false';
        $passwordErrorClass = $passwordError ? 'true' : 'false';
        
        echo <<<HTML
            <div class="container">
                <div class="center-wrapper">
                    <div class="form-card">
                        <h2>{$titulo->getContenido()}</h2>
                        <form action="index.php?Login/login" method="POST">
                            <label for="login-email">{$labelEmail->getContenido()}</label>
                            <input type="email" id="login-email" name="email" value="{$emailValue}" required class="input-error-{$emailErrorClass}">
                            <div class="error-message">{$emailError}</div>
                            
                            <label for="login-password">{$labelPassword->getContenido()}</label>
                            <input type="password" id="login-password" name="password" required class="input-error-{$passwordErrorClass}">
                            <div class="error-message">{$passwordError}</div>
                            
                            <button type="submit" class="cta-button">{$buttonText->getContenido()}</button>
                        </form>
                        <div class="error-message general-error">{$generalError}</div>
                        <div class="link-signup">
                            <p>{$registroPregunta->getContenido()} <a href="index.php?Register/show">{$registroEnlace->getContenido()}</a></p>
                        </div>
                    </div>
                </div>
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
        </body>
        </html>
        HTML;
    }
}
