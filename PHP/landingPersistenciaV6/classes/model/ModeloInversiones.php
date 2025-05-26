<?php
class ModeloInversiones
{

    function cogerValoresWebScraping($fila, $posInicial, $posFinal)
    {
        $posIniNom = strpos($fila, $posInicial);
        $posIniNom += strlen($posInicial);

        if (strpos($posInicial, '>') === false) {
            $posIniNom += 4;
        }

        $posFinalPos = strpos($fila, $posFinal, $posIniNom);
        $valor = trim(substr($fila, $posIniNom, $posFinalPos - $posIniNom));

        return $valor;
    }

    function encontrarTicker($html)
    {
        $posIniNom = 0;
        $stringNom =  $this->cogerValoresWebScraping($html, 'value="N" />', '</td>');
        $posicioNom = strpos($html, $stringNom) + strlen($stringNom);

        $posIniNom = strpos($html, '<td>', $posicioNom);
        $posIniNom += strlen('<td>');
        $posFinalPos = strpos($html, '</td>', $posIniNom);

        $ticker = trim(substr($html, $posIniNom, $posFinalPos - $posIniNom));

        return $ticker;
    }

    function encontrarMercat($html)
    {
        $posIniNom = 0;

        $posIniNom = strpos($html, '<td>', $posIniNom);
        $posIniNom += strlen('<td>');
        $posFinalNom = strpos($html, '</td>', $posIniNom);

        $posIni2 = strpos($html, '<td>', $posFinalNom) + 4;
        $posFinalPos = strpos($html, '</td>', $posIni2);

        $mercat = trim(substr($html, $posIni2, $posFinalPos - $posIni2));

        return $mercat;
    }

    function dibujarArray($html)
    {
        $bolsa = [];

        $recortarHtmlInicio = strpos($html, '<table id="tabCotizaciones" class="tabDatosInterior1">');
        $recortarHtmlfinal = strpos($html, '</table>', $recortarHtmlInicio);
        $long = $recortarHtmlfinal - $recortarHtmlInicio;

        $htmlRecortadao = substr($html, $recortarHtmlInicio, $long);
        $htmlRecortadao = preg_replace('/\s+/', ' ', $htmlRecortadao);

        $filas = explode('<tr', $htmlRecortadao);

        foreach ($filas as $fila) {
            if (strpos($fila, 'field="volumen">') !== false) {
                $bolsa[] = [
                    "nom" =>  $this->cogerValoresWebScraping($fila, 'value="N" />', '</td>'),
                    "ticker" =>  $this->encontrarTicker($fila),
                    "mercat" =>  $this->encontrarMercat($fila),
                    "ultima_coti" =>  $this->cogerValoresWebScraping($fila, 'field="precio_ultima_cotizacion">', '</span>'),
                    "divisa" =>  $this->cogerValoresWebScraping($fila, '<td class="ac">', '</td>'),
                    "variacio" =>  $this->cogerValoresWebScraping($fila, 'id="tdDif_', '</span>'),
                    "variacio_percent" =>  $this->cogerValoresWebScraping($fila, 'id="tdPorcDif_', '</span>'),
                    "volum" =>  $this->cogerValoresWebScraping($fila, 'field="volumen">', '</span>'),
                    "mínim" =>  $this->cogerValoresWebScraping($fila, 'field="minimo">', '</span>'),
                    "màxim" =>  $this->cogerValoresWebScraping($fila, 'field="maximo">', '</span>'),
                    "data" =>  $this->cogerValoresWebScraping($fila, 'field="fecha_hora_cotizacion">', '</span>'),
                    "hora" =>  $this->cogerValoresWebScraping($fila, 'field="hora_ultima_cotizacion_instituc">', '</span>')
                ];
            }
        }
        return $bolsa;
    }

    function obtenerDatosCotizaciones()
    {
        $url = "https://www.inversis.com/inversiones/productos/cotizaciones-nacionales&pathMenu=2_3_0&esLH=N";
        $html = file_get_contents($url);
        
        if ($html === false) {
            die("No se ha podido obtener la web");
        }
        
        $datos = $this->dibujarArray($html);
        
        if (empty($datos)) {
            die("No se han podido extraer datos válidos del HTML.");
        }
        
        return $this->dibujarArray($html);
    }
}

