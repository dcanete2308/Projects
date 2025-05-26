<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

//$url = "https://www.inversis.com/trans/inversis/SvlCotizaciones?accion=cotizacionesValores&codigoIndice=3";
$url = "https://www.inversis.com/inversiones/productos/cotizaciones-nacionales&pathMenu=2_3_0&esLH=N";
$html = file_get_contents($url);

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($html === false) {
    die("No se ha podido obtener la web");
}

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
    $stringNom = cogerValoresWebScraping($html, 'value="N" />', '</td>');
    $posicioNom = strpos($html, $stringNom) + strlen($stringNom);

    $posIniNom = strpos($html, '<td>', $posicioNom);
    $posIniNom += strlen('<td>');
    $posFinalPos = strpos($html, '</td>', $posIniNom);

    $ticker = trim(substr($html, $posIniNom, $posFinalPos - $posIniNom));

    $posIniNom = $posFinalPos + strlen('</td>') + 15;

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

    $posIniNom = $posFinalPos + strlen('</td>');

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

                "nom" => cogerValoresWebScraping($fila, 'value="N" />', '</td>'),
                "ticker" => encontrarTicker($fila),
                "mercat" => encontrarMercat($fila),
                "ultima_coti" => cogerValoresWebScraping($fila, 'field="precio_ultima_cotizacion">', '</span>'),
                "divisa" => cogerValoresWebScraping($fila, '<td class="ac">', '</td>'),
                "variacio" => cogerValoresWebScraping($fila, 'id="tdDif_', '</span>'),
                "variacio_percent" => cogerValoresWebScraping($fila, 'id="tdPorcDif_', '</span>'),
                "volum" => cogerValoresWebScraping($fila, 'field="volumen">', '</span>'),
                "mínim" => cogerValoresWebScraping($fila, 'field="minimo">', '</span>'),
                "màxim" => cogerValoresWebScraping($fila, 'field="maximo">', '</span>'),
                "data" => cogerValoresWebScraping($fila, 'field="fecha_hora_cotizacion">', '</span>'),
                "hora" => cogerValoresWebScraping($fila, 'field="hora_ultima_cotizacion_instituc">', '</span>')
            ];
        }
    }
    return $bolsa;
}

$datos = dibujarArray($html);

echo '</header>';
echo '<h1>Datos de Cotizaciones</h1>';
echo '<table>';
echo '<tr>
        <th>Nombre</th>
        <th>Ticker</th>
        <th>Mercado</th>
        <th>Última Cotización</th>
        <th>Divisa</th>
        <th>Variación</th>
        <th>Variación %</th>
        <th>Volumen</th>
        <th>Mínimo</th>
        <th>Máximo</th>
        <th>Fecha</th>
        <th>Hora</th>
      </tr>';

$count = 0;

foreach ($datos as $fila) {
    $color = "";

    if (! isset($_SESSION["sesionAnterior"])) {
        $_SESSION["sesionAnterior"] = [];
    }

    if (isset($_SESSION["sesionActual"][$count])) {
        $_SESSION['sesionAnterior'][$count] = $_SESSION['sesionActual'][$count];
    }

    if (isset($_SESSION["sesionActual"][$count])) {
        $_SESSION['sesionAnterior'][$count] = $_SESSION['sesionActual'][$count];
    } else {
        $_SESSION['sesionAnterior'][$count] = null; 
    }

    if (isset($_SESSION["sesionActual"][$count]) && isset($_SESSION["sesionActual"][$count])) {
        if ($_SESSION["sesionActual"][$count] < $_SESSION["sesionAnterior"][$count]) {
            $color = "red";
        } elseif ($_SESSION["sesionActual"][$count] > $_SESSION["sesionAnterior"][$count]) {
            $color = "green";
        } else {
            $color = "";
        }
    }

    echo '<tr>
            <td>' . htmlspecialchars($fila['nom']) . '</td>
            <td>' . htmlspecialchars($fila['ticker']) . '</td>
            <td>' . htmlspecialchars($fila['mercat']) . '</td>
            <td class="' . $color . '">' . htmlspecialchars($fila['ultima_coti']) . '</td>
            <td>' . htmlspecialchars($fila['divisa']) . '</td>
            <td class="' . ($fila['variacio'] < 0 ? 'red' : 'green') . '">' . htmlspecialchars($fila['variacio']) . '</td>
            <td class="' . ($fila['variacio_percent'] < 0 ? 'red' : 'green') . '">' . htmlspecialchars($fila['variacio_percent']) . '</td>
            <td>' . htmlspecialchars($fila['volum']) . '</td>
            <td>' . htmlspecialchars($fila['mínim']) . '</td>
            <td>' . htmlspecialchars($fila['màxim']) . '</td>
            <td>' . htmlspecialchars($fila['data']) . '</td>
            <td>' . htmlspecialchars($fila['hora']) . '</td>
          </tr>';

    $count ++;
}

echo '</table>';

?>

<style>
body {
	font-family: 'Arial', sans-serif;
	background-color: #f0f8ff;
	color: #333;
	margin: 20px;
	padding: 20px;
}

h1 {
	text-align: center;
	color: #003366;
	font-size: 2.5em;
	margin-bottom: 20px;
}

pre {
	background-color: #ffffff;
	padding: 15px;
	border: 1px solid #ccc;
	border-radius: 8px;
	overflow-x: auto;
	white-space: pre-wrap;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

table {
	width: 100%;
	border-collapse: collapse;
	margin-top: 20px;
}

th, td {
	padding: 12px;
	text-align: left;
	border: 2px solid #000;
	background-color: #ffffff;
}

th {
	background-color: #003366;
	color: #FFD700;
}

tr:hover {
	background-color: #e0f7fa;
}

.red {
	background-color: #e74c3c;
	font-weight: bold;
}

.green {
	background-color: #2ecc71;
	font-weight: bold;
}

td {
	color: #003366;
}
</style>


