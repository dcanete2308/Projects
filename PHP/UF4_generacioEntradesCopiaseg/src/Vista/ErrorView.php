<?php
namespace Entradas\Vista;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class ErrorView
{

    public function showError()
    {
        $css = file_get_contents('./src/static/error.css');

        $html = "
        <body>
            <div class='background'></div>
            <div class='error-box'>
                <strong>Error</strong>
                <p>La entrada no existe.</p>
            </div>
        </body>";

        $mpdf = new Mpdf([
            'format' => [80,40],
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0
        ]);

        $mpdf->SetTitle("Entrada No Existente");
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS); // mete el css
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output("entrada_no_existente.pdf", Destination::INLINE); // enseña el pdf de error
    }
}
    