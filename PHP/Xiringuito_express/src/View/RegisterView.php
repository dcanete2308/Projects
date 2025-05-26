<?php
namespace Xiringuito\View;

class RegisterView
{

    public static function show($datos, $errors = [], $sesionIniciada = false)
    {
        $nameValue = htmlspecialchars($datos['name']);
        $surnameValue = htmlspecialchars($datos['surname']);
        $emailValue = htmlspecialchars($datos['email']);

        $nameError = $errors['name'] ?? '';
        $surnameError = $errors['surname'] ?? '';
        $emailError = $errors['email'] ?? '';
        $passwordError = $errors['password'] ?? '';
        $repeatPasswordError = $errors['password_repeat'] ?? '';
        $termsError = $errors['termsLabel'] ?? '';
        $generalError = $errors['general'] ?? '';

        $nameErrorClass = $nameError ? 'true' : 'false';
        $surnameErrorClass = $surnameError ? 'true' : 'false';
        $emailErrorClass = $emailError ? 'true' : 'false';
        $passwordErrorClass = $passwordError ? 'true' : 'false';
        $repeatPasswordErrorClass = $repeatPasswordError ? 'true' : 'false';
        

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
            <div class="center-wrapper">
                <div class="form-card">
                    <h2>{$datos['titulo']->getContenido()}</h2>
                    <form action="index.php?Register/register" method="POST">
                        <label for="signup-name">{$datos['labelNombre']->getContenido()}<span>*</span></label>
                        <input type="text" id="signup-name" name="name" value="{$nameValue}" required class="input-error-{$nameErrorClass}">
                        <div class="error-message">{$nameError}</div>
                        
                        <label for="signup-surname">{$datos['labelApellido']->getContenido()}</label>
                        <input type="text" id="signup-surname" name="surname" value="{$surnameValue}" class="input-error-{$surnameErrorClass}">
                        <div class="error-message">{$surnameError}</div>
                        
                        <label for="signup-email">{$datos['labelEmail']->getContenido()}<span>*</span></label>
                        <input type="email" id="signup-email" name="email" value="{$emailValue}" required class="input-error-{$emailErrorClass}">
                        <div class="error-message">{$emailError}</div>
                        
                        <label for="signup-password">{$datos['labelPassword']->getContenido()}<span>*</span></label>
                        <input type="password" id="signup-password" name="password" required class="input-error-{$passwordErrorClass}">
                        <div class="error-message">{$passwordError}</div>
                        
                        <label for="signup-password-repeat">{$datos['labelRepeatPassword']->getContenido()}<span>*</span></label>
                        <input type="password" id="signup-password-repeat" name="password_repeat" required class="input-error-{$repeatPasswordErrorClass}">
                        <div class="error-message">{$repeatPasswordError}</div>
                        <label>
                            <input type="checkbox" name="accept_terms" required>
                            {$datos['termsLabel']->getContenido()}<span>*</span>
                        </label>
                        <div class="error-message">{$termsError}</div>
                        <details style="margin-top: 10px; font-size: 0.9em;">
                            <summary style="cursor: pointer; color: #007BFF; text-decoration: underline;">{$datos['termsTitle']->getContenido()}</summary>
                            <div style="margin-top: 8px; background-color: #f9f9f9; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                                <p>
                                    {$datos['terms']->getContenido()}
                                </p>
                            </div>
                        </details>
                        <button type="submit" class="cta-button">{$datos['buttonText']->getContenido()}</button>
                    </form>
                    <div class="error-message general-error">{$generalError}</div>
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