<?php
class Controller
{

    public function sanitize($valor)
    {
        $valor = htmlspecialchars($valor);
        $valor = stripcslashes($valor);
        return $valor;
    }

    public function validateItem($var, $type)
    {
        $regexes = Array(
            'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
            'amount' => "^[-]?[0-9]+\$",
            'number' => "^[-]?[0-9,]+\$",
            'nom' => "^[a-zA-ZñÑàáèéíòóúÀÁÈÉÍÒÓÚçÇ' .-]*$",
            'alfanum' => "^[0-9a-zA-ZñÑàáèéíòóúÀÁÈÉÍÒÓÚçÇ' ,.-_\\s\?\!]*$",
            'not_empty' => "[a-z0-9A-Z]+",
            'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
            'phone' => "^[0-9]{9,11}\$",
            'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
            'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
            'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
            '2digitopt' => "^\d+(\,\d{2})?\$",
            '2digitforce' => "^\d+\,\d\d\$",
            'anything' => "^[\d\D]{1,}\$"
        );
        if (array_key_exists($type, $regexes)) {
            $returnval = filter_var($var, FILTER_VALIDATE_REGEXP, array(
                "options" => array(
                    "regexp" => '!' . $regexes[$type] . '!i'
                )
            )) !== false;
            return ($returnval);
        }
        $filter = false;
        switch ($type) {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
                break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
                break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
                break;
        }
        return ($filter === false) ? false : (filter_var($var, $filter) !== false ? true : false);
    }
}

