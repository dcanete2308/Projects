<?php

class DaoTema
{
    
    private $db;
    
    public function __construct()
    {
        $this->db = DataBasePDO::getInstance()->getConnection();
    }
    
    /**
     * devuelve los datos del tema
     * @return TemaModel[] obj de negocio
     */
    public function selectAll()
    {
        $query = "SELECT
                t.id AS temaId,
                t.nom AS nombreTema,
                t.descripcio AS descripcionTema,
                COUNT(p.id) AS numFrases
              FROM
                tbl_themes t
              LEFT JOIN
                tbl_phrases p ON p.theme_id = t.id
              GROUP BY
                t.id;";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $temas = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $tema = new TemaModel(
                $row->temaId,
                $row->nombreTema,
                $row->descripcionTema,
                $row->numFrases
                );
            $temas[] = $tema;
        }
        
        return $temas;
    }
    
    /**
     * seleciona todos los de la pagina en espcifico
     * @param int $limite donde empieza
     * @param int $offset los que coge
     */
    public function selectAllLimit($limite, $offset)
    {
        $query = "SELECT
                t.id AS temaId,
                t.nom AS nombreTema,
                t.descripcio AS descripcionTema,
                COUNT(p.id) AS numFrases
              FROM
                tbl_themes t
              LEFT JOIN
                tbl_phrases p ON p.theme_id = t.id
              GROUP BY
                t.id
             LIMIT :lim 
             OFFSET :of";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lim', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':of', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $temas = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $tema = new TemaModel(
                $row->temaId,
                $row->nombreTema,
                $row->descripcionTema,
                $row->numFrases
                );
            $temas[] = $tema;
        }
        
        return $temas;
    }
    /**
     * devuelve un tema en especifico en base a su id
     * @param int $id id a buscar
     * @return TemaModel|NULL
     */
    public function selectById($id)
    {
        $query = "SELECT
                t.id AS temaId,
                t.nom AS nombreTema,
                t.descripcio AS descripcionTema,
                COUNT(p.id) AS numFrases
              FROM
                tbl_themes t
              LEFT JOIN
                tbl_phrases p ON p.theme_id = t.id
              WHERE
                t.id = :id
              GROUP BY
                t.id;";
        
        if (is_array($id)) {
            $id = $id['id'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($row) {
            return new TemaModel(
                $row->temaId,
                $row->nombreTema,
                $row->descripcionTema,
                $row->numFrases
                );
        } else {
            return null;
        }
    }
    
    /**
     * busca por nombre que entra por parametro un tema para ver si existe
     * @param string $nombre del tema que busca
     * @return mixed
     */
    public function selectByName($nombre) {
        $query = "SELECT id FROM tbl_themes WHERE nom = :nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    /**
     * seleciona todos los temas que existen
     * @return array
     */
    public function selectAllThemes() {
        $query = "SELECT id, nom FROM tbl_themes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * elimina un tema en especifico
     * @param int $id bucs por id
     */
    public function deleteTema($id) {
        $query = "DELETE FROM tbl_themes WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * actualiza un tema que ha encontrado por el id de este
     * @param int $id
     * @param string $nom
     * @param string $desc
     */
    public function updateTema($id, $nom, $desc) {
        $query = "UPDATE tbl_themes set nom = :nom, descripcio = :desc WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    /**
     * inserta nuevos temas en la bd
     * @param string $nom
     * @param string $desc
     * @return string|boolean
     */
    public function insertTema($nom, $desc){
        if (empty($nom)) {
            $nom = '';
        }
        
        if (empty($desc)) {
            $desc = '';
        }
        
        $query = "INSERT INTO tbl_themes (nom, descripcio) VALUES (:nom, :descripcio)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
        $stmt->bindParam(":descripcio", $desc, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * filtra por los parametros que recibe, no busca exacto sino que busca por un rlike
     * @param string $nombre
     * @param string $descripcion
     * @return TemaModel[] obj de negocio
     */
    public function filtrar($nombre = '', $descripcion = '') {
        $query = "SELECT
                t.id AS temaId,
                t.nom AS nombreTema,
                t.descripcio AS descripcionTema,
                COUNT(p.id) AS numFrases
              FROM
                tbl_themes t
              LEFT JOIN
                tbl_phrases p ON p.theme_id = t.id
              WHERE
                (:nombre = '' OR t.nom RLIKE :nombre)
                AND (:descripcion = '' OR t.descripcio RLIKE :descripcion)
              GROUP BY
                t.id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $temas = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $tema = new TemaModel(
                $row->temaId,
                $row->nombreTema,
                $row->descripcionTema,
                $row->numFrases
                );
            $temas[] = $tema;
        }
        
        return $temas;
    }
    
    
    
}
