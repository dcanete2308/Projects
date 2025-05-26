<?php
class CalendarioController extends Controller
{
    public function __construct() {
    }
    
    public function show($params = null) {
        $currentMonth = isset($params[0]) ? (int)$params[0] : date('n') - 1; // si no se ha pasado por paramero el mes, cojemos el mes actual con date
        $currentYear = isset($params[1]) ? (int)$params[1] : date('Y'); // si no se ha pasado por paramero el año, cojemos el año actual con date
        
        
        if ($currentMonth < 0) { // si el mes es menor a 0 pasa a diciembre directo y le restamos 1 año
            $currentMonth = 11;
            $currentYear--;
        } elseif ($currentMonth > 11) { // si el mes es mayor a 11 pasa a enero y sumamos 1 año
            $currentMonth = 0;
            $currentYear++;
        }
        
        $diasEnElMes = cal_days_in_month(CAL_GREGORIAN, $currentMonth + 1, $currentYear); // conseguimos los dias que tiene el mes en base al año y mes entrado
        
        $primerDiaDelMes = (int)date('w', strtotime("$currentYear-" . ($currentMonth + 1) . "-01")); // se consigue el primer dia de la semana, que va del 0 al 6
        
        $diasMesAnteriorMes = cal_days_in_month(CAL_GREGORIAN, ($currentMonth == 0 ? 12 : $currentMonth), $currentYear); //conseguimos los dias del mes anterior
        
        $diasMesAnterior = $diasMesAnteriorMes - $primerDiaDelMes + 1;
        
        $diasSiguenteMes = 42 - ($primerDiaDelMes + $diasEnElMes); // dias que hemos de mostrar en el calendario
        
        $primerDiaCalendarioDateTime = (new DateTime("$currentYear-" . ($currentMonth + 1) . "-01")) ->modify("-$primerDiaDelMes days"); //restamos los dias del mes anterior y así conseguimos el primer dia
        
        $ultimoDiaCalendarioDateTime = (new DateTime("$currentYear-" . ($currentMonth + 1) . "-01")) ->modify("+$diasEnElMes days") ->modify("+$diasSiguenteMes days"); // misma logica que el anterior pero le sumamos los dias en el mes actual y el siguiente
        
        
        $bd = eventModel::getBetween($primerDiaCalendarioDateTime->format("Y-m-d"), $ultimoDiaCalendarioDateTime->format("Y-m-d")); // conseguimos los eventos que estan entre el primer dia que muestra  el calendario y el ultimo dia que muestra el calendario
        $resultado = $bd->fetch_all(MYSQLI_NUM);
        $events = [];
        foreach ($resultado as $r) {
            $events[] = [
                "id" => $r[0],
                "titulo" => $r[1],
                "fecha_ini" => $r[2],
                "hora_ini" => $r[3],
                "fecha_fin" => $r[4],
                "hora_fin" => $r[5],
                "etiqueta" => $r[6],
                "desc" => $r[7]
            ];
        }
        
        $vCalendario = new CalendarioView();
        $vCalendario->show([
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'diasEnElMes' => $diasEnElMes,
            'primerDiaDelMes' => $primerDiaDelMes,
            'diasMesAnterior' => $diasMesAnterior,
            'diasSiguenteMes' => $diasSiguenteMes,
            'diasMesAnteriorMes' => $diasMesAnteriorMes,
            'primerDiaCalendario' => $primerDiaCalendarioDateTime->format("Y-m-d"),
            'ultimoDiaCalendario' => $ultimoDiaCalendarioDateTime->format("Y-m-d")
        ], $events);
        
        
    }
}

