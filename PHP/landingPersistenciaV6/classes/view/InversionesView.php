<?php

class InversionesView
{

    public function showInversiones($datos)
    {
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Cotizaciones</title>
    <link rel="stylesheet" href="../css/inversiones.css">
</head>';

        echo '<body>
    <header>
        <h1>Datos de Cotizaciones</h1>
    </header>';

        echo '<table>
        <tr>
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

        foreach ($datos as $fila) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['ticker']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['mercat']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['ultima_coti']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['divisa']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['variacio']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['variacio_percent']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['volum']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['mínim']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['màxim']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['data']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['hora']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</body>";
        echo "</html>";
    }
}

