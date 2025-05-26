<?php

namespace Entradas\Vista;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Entradas\Entity\Ticket;

class TicketView
{

    public function showTicket(Ticket $ticket)
    {

        $css = file_get_contents('./src/static/estilos.css');
        $compra = $ticket->getPurchase();
        $usuari = $compra->getUser();
        $esdeveniment = $ticket->getEvent();
        $localitzacio = $esdeveniment->getVenue();
        $seient = $ticket->getSeat();
        $referencia = $ticket->getCode();

        $mpdf = new Mpdf([
            'format' => [
                210,
                400
            ]
        ]);
//         $mpdf->SetProtection([
//             'print'
//         ], '2025@Thos');
        $mpdf->SetWatermarkText('TICKDID');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        $barcodeHtml = '<barcode code="' . $referencia . '" type="C128A" size="1" height="1.2" />';
        $qrUrl = "http://localhost/UF4_generacioEntrades2/index.php?ref=" . urlencode($referencia);
        $qrHtml = "<barcode code='$qrUrl' type='QR' size='1.2' height='1' />";

        $precio = $ticket->getPrice() . ' €';

        $html = "
    <div class='entrada-container'>
        <div class='entrada-header'>
            ENTRADA - {$esdeveniment->getTitle()}
        </div>
        
        <div class='entrada-contenido'>
                <img src='{$ticket->getImg()}' />
            <div class='entrada-img'>
            </div>
            
            <div class='datos'>
                <div class='entradaSeccion'>
                    <div class='entradaDatos'>
                        <strong>Esdeveniment:</strong>
                        <span>{$esdeveniment->getTitle()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Descripció:</strong>
                        <span>{$esdeveniment->getDescription()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Lloc:</strong>
                        <span>{$localitzacio->getName()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Adreça:</strong>
                        <span>{$localitzacio->getAddress()}, {$localitzacio->getCity()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Data:</strong>
                        <span>{$esdeveniment->getStartTime()->format('d/m/Y H:i')} - {$esdeveniment->getEndTime()->format('H:i')}</span>
                    </div>
                </div>
                
                <div class='entradaSeccion'>
                    <div class='entradaDatos'>
                        <strong>Referència:</strong>
                        <span class='highlight'>{$referencia}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Preu:</strong>
                        <span>{$precio}</span>
                    </div>";

        if ($seient) {
            $html .= "
                    <div class='entradaDatos'>
                        <strong>Seient:</strong>
                        <span>Fila {$seient->getRow()}, Número {$seient->getNumber()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Tipus Seient:</strong>
                        <span>{$seient->getType()}</span>
                    </div>";
        }

        if ($compra && $usuari) {
            $html .= "
                    <div class='entradaDatos'>
                        <strong>Núm. Compra:</strong>
                        <span>{$compra->getId()}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Data Compra:</strong>
                        <span>{$compra->getPurchaseDate()->format('d/m/Y H:i')}</span>
                    </div>
                    <div class='entradaDatos'>
                        <strong>Comprador:</strong>
                        <span>{$usuari->getName()} ({$usuari->getEmail()})</span>
                    </div>";
        }

        $html .= "
                </div>
            </div>
        </div>
        
        <div class='codigos'>
            <div class='codigoCont'>
                <h3>CODI DE BARRES</h3>
                {$barcodeHtml}
                <p style='font-size:10px; margin-top:5px;'>{$referencia}</p>
            </div>
            <div class='codigoCont'>
                <h3>CODI QR</h3>
                {$qrHtml}
                <p style='font-size:10px; margin-top:5px;'>Escaneja per validar</p>
            </div>
        </div>
        
        <div class='footer'>
            <img src='./src/static/media/Tickdid.png' alt='Logo TICKDID' class='footerLogo'>
            <p>© 2025 TICKDID - Tots els drets reservats</p>
            <p>No vàlida si ha estat fotocopiada o alterada</p>
        </div>
    </div>";

        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output("ticket_{$referencia}.pdf", Destination::INLINE);
    }
}