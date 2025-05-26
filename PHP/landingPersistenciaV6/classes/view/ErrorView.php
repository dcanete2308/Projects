<?php
class ErrorView
{
    public static function show($e) {
        
        echo "<!DOCTYPE html><html lang=\"en\">";
        echo "<head><meta charset=\"UTF-8\"><title>Error</title>";
        echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0 auto;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
    }
    .error-container {
        background-color: #fff;
        border: 2px solid #e74c3c;
        border-radius: 10px;
        padding: 30px;
        width: 60%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .error-container h1 {
        color: #e74c3c;
        font-size: 2em;
        margin: 20px 0;
    }
    .error-container p {
        color: #333;
        font-size: 1.1em;
    }
    .error-container pre {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 5px;
        text-align: left;
        margin-top: 20px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .error-image {
        width: 150px;
        height: 150px;
        margin: 20px auto;
    }
</style></head>";
        echo "<body>";
        echo "<main>";
        echo "<div class='error-container'>";
        echo "<img src='../media/error-cruz-roja-dentro-de-circulo-rojo.png' alt='Error' class='error-image'>";
        echo "<h1>Ocurrió un error</h1>";
        echo "<p>Lo sentimos, algo salió mal.</p>";
        echo "<p>Por favor, intenta nuevamente más tarde.</p>";
        echo "<p><strong>Error técnico:</strong></p>";
        echo "<pre>" . $e . "</pre>";
        echo "</div>";
        echo "</main>";
        echo "</body></html>";
    }
}

