<?php

class eventModel
{

    /**
     * Coje toda la bd
     * @return mysqli_result|boolean
     */
    public static function getBD()
    {
        $bd = DataBase::getInstance();
        $query = "SELECT * FROM eventos";
        return $bd->execute([], "", $query);
    }

    /**
     * Coje entre dos fechas
     * @param $ini 
     * @param  $fi 
     * @return mysqli_result|boolean
     */
    public static function getBetween($ini, $fi)
    {
        if (! empty($ini) && ! empty($fi)) {
            $bd = DataBase::getInstance();
            $query = "SELECT * FROM eventos where (fecha_inicio between ? and ?)
            or (fecha_inicio < ? and fecha_fin > ?) or (fecha_fin between ? and ?)";
            return $bd->execute([
                $ini,
                $fi,
                $ini,
                $fi,
                $ini,
                $fi
            ], "ssssss", $query);
        }
        ;
    }

    /**
     * inserta un nuevo evento
     * @param $evento 
     */
    public static function insertEvent($evento)
    {
       
        $nombre = trim($evento->__get("nombre_evento"));
        $fecha_inicio = trim($evento->__get("fecha_inicio"));
        $hora_inicio = trim($evento->__get("hora_inicio"));
        $fecha_fin = trim($evento->__get("fecha_fin"));
        $hora_fin = trim($evento->__get("hora_fin"));
        $etiqueta = trim($evento->__get("etiqueta"));
        $desc = trim($evento->__get("descripcion"));
        
        if (empty($hora_inicio)) {
            $hora_inicio = null;
        }
        
        if (empty($hora_fin)) {
           $hora_fin = null;
        }

        if (empty($desc)) {
            $desc = null;
        }
                
        if (! empty($nombre) && ! empty($fecha_inicio) && ! empty($fecha_fin)) {
            $bd = DataBase::getInstance();
            $query = "SELECT * FROM eventos WHERE nombre_evento = ? AND fecha_inicio = ? AND hora_inicio = ? AND fecha_fin = ? AND hora_fin = ? AND etiqueta = ? AND descripcion = ?";
            $eventoRepe = $bd->execute([
                $nombre,
                $fecha_inicio,
                $hora_inicio,
                $fecha_fin,
                $hora_fin,
                $etiqueta,
                $desc
            ], "sssssss", $query); // comprueba si existe el evento
            var_dump($eventoRepe);

            if ($eventoRepe && $eventoRepe->num_rows > 0) { // significa que existe
                return;
            }

            $query2 = "INSERT INTO eventos (nombre_evento, fecha_inicio, hora_inicio, fecha_fin, hora_fin, etiqueta, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $bd->execute([
                $nombre,
                $fecha_inicio,
                $hora_inicio,
                $fecha_fin,
                $hora_fin,
                $etiqueta,
                $desc
            ], "sssssss", $query2);
            echo "datos insertados, bien";
        }
        
    }

    /**
     * Elimiona toda la bd
     * @return mysqli_result|boolean
     */
    public static function deleteAll()
    {
        $bd = DataBase::getInstance();
        $query = "DELETE FROM eventos";
        return $bd->execute([], "", $query);
    }

    /**
     * elimina un dato de la bd
     * @param $evento  
     * @return mysqli_result|boolean
     */
    public static function deleteEspecific($evento)
    {
        $bd = DataBase::getInstance();
        $query = "DELETE FROM eventos where id = ?";
        return $bd->execute([
            $evento->__get("id")
        ], "i", $query);
    }
   
    /**
     * actualiza datos de la bd
     * @param  $evento
     * @return boolean|mysqli_result|boolean
     */
    public static function updateEvent($evento)
    {

        $nombre = "";
        $fecha_inicio = "";
        $hora_inicio = "";
        $fecha_fin = "";
        $hora_fin = "";
        $etiqueta = "";
        $descripcion = "";
        
        $bd = DataBase::getInstance();
        $query = "SELECT * FROM eventos WHERE id = ?";
        $execute = $bd->execute([$evento->id], "i", $query);
        $resultado = $execute->fetch_all(MYSQLI_NUM);
        
        if (empty($resultado)) {
            return false;
        }
        
        if (empty($evento->__get("nombre_evento"))) {
            $nombre = $resultado[0][1];
        } else {
            $nombre = $evento->__get("nombre_evento");
        }
        
        if (empty($evento->__get("fecha_inicio"))) {
            $fecha_inicio = $resultado[0][2];
        } else {
            $fecha_inicio = $evento->__get("fecha_inicio");
        }
        
        if (empty($evento->__get("hora_inicio"))) {
            $hora_inicio = $resultado[0][3];
        } else {
            $hora_inicio = $evento->__get("hora_inicio");
        }
        
        if (empty($evento->__get("fecha_fin"))) {
            $fecha_fin = $resultado[0][4];
        } else {
            $fecha_fin = $evento->__get("fecha_fin");
        }
        
        if (empty($evento->__get("hora_fin"))) {
            $hora_fin = $resultado[0][5];
        } else {
            $hora_fin = $evento->__get("hora_fin");
        }
        
        if (empty($evento->__get("etiqueta"))) {
            $etiqueta = $resultado[0][6];
        } else {
            $etiqueta = $evento->__get("etiqueta");
        }
        
        if (empty($evento->__get("descripcion"))) {
            $descripcion = $resultado[0][7];
        } else {
            $descripcion = $evento->__get("descripcion");
        }

        $query2 = "UPDATE eventos
        SET nombre_evento = ?,
            fecha_inicio = ?,
            hora_inicio = ?,
            fecha_fin = ?,
            hora_fin = ?,
            etiqueta = ?,
            descripcion = ?
        WHERE id = ?";
        
        return $bd->execute([$nombre, $fecha_inicio, $hora_inicio, $fecha_fin, $hora_fin, $etiqueta, $descripcion, $evento->id], "sssssssi", $query2);
    }

    
    /**
     * filtra por etiquta, nombre o las dos
     * @param  $evento 
     * @return mysqli_result|boolean
     */
    public static function filtrar($evento) {
        $bd = DataBase::getInstance();
        $nombre = $evento->__get("nombre_evento");
        $etiqueta = $evento->__get("etiqueta");
        
        if (!empty($nombre) && !empty($etiqueta)) {
            $query = "SELECT * FROM eventos WHERE nombre_evento LIKE ? AND etiqueta = ?";
            return $bd->execute(["%$nombre%", $etiqueta], "ss", $query);
        }
        
        if (!empty($nombre)) {
            $query = "SELECT * FROM eventos WHERE nombre_evento LIKE ?";
            return $bd->execute(["%$nombre%"], "s", $query);
        }
        
        if (!empty($etiqueta)) {
            $query = "SELECT * FROM eventos WHERE etiqueta = ?";
            return $bd->execute([$etiqueta], "s", $query);
        }
        
        return $bd->execute([], "", "SELECT * FROM eventos");
    }
    
    
}

