<?php

class DaoFrase
{
    
    private $db;
    
    public function __construct()
    {
        $this->db = DataBasePDO::getInstance()->getConnection();
    }
    
    /**
     * enseña todas las frases que hay
     * @return FraseModel[] obj de negocio
     */
    public function selectAll()
    {
        $query = "SELECT
                p.id AS fraseId,
                p.texto AS texto,
                a.nom AS nombreAutor,
                t.nom AS nombreTema,
                p.created_at AS creacion,
                p.updated_at AS updated
            FROM
                tbl_phrases p
            LEFT JOIN
                tbl_authors a ON p.author_id = a.id
            LEFT JOIN
                tbl_themes t ON p.theme_id = t.id;";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $frases = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $frase = new FraseModel($row->fraseId,
                $row->texto,
                $row->nombreAutor,
                $row->nombreTema,
                $row->creacion,
                $row->updated);
            $frases[] = $frase;
        }
        
        return $frases;
    }
    
    /**
     * seleciona todos los de la pagina en espcifico
     * @param int $limite donde empieza
     * @param int $offset los que coge
     */
    public function selectAllLimit($limite, $offset)
    {
        $query = "SELECT
                p.id AS fraseId,
                p.texto AS texto,
                a.nom AS nombreAutor,
                t.nom AS nombreTema,
                p.created_at AS creacion,
                p.updated_at AS updated
            FROM
                tbl_phrases p
            LEFT JOIN
                tbl_authors a ON p.author_id = a.id
            LEFT JOIN
                tbl_themes t ON p.theme_id = t.id
            LIMIT :lim 
            OFFSET :of";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lim', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':of', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $frases = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $frase = new FraseModel($row->fraseId,
                $row->texto,
                $row->nombreAutor,
                $row->nombreTema,
                $row->creacion,
                $row->updated);
            $frases[] = $frase;
        }
        
        return $frases;
    }
    
    
    /**
     * devuelve un select de una frase en especifico, que lo busca por id
     * @param int $id a buscar
     * @return FraseModel|NULL obj de negocio
     */
    public function selectById($id) {
        $query = "SELECT
                p.id AS fraseId,
                p.texto AS texto,
                a.nom AS nombreAutor,
                t.nom AS nombreTema,
                p.created_at AS creacion,
                p.updated_at AS updated
              FROM
                tbl_phrases p
              JOIN
                tbl_authors a ON p.author_id = a.id
              JOIN
                tbl_themes t ON p.theme_id = t.id
              WHERE
                p.id = :id;";
        
        if (is_array($id)) {
            $id = $id['id'];
        }
        
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($row) {
            return new FraseModel(
                $row->fraseId,
                $row->texto,
                $row->nombreAutor,
                $row->nombreTema,
                $row->creacion,
                $row->updated
                );
        } else {
            return null;
        }
    }
    
    /**
     * devuelve el autor por nombre especifico
     * @param string $nom nombre del autor
     * @return mixed
     */
    public function selectAutor($nom){
        $query = "SELECT id FROM tbl_authors where nom = :nom";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * busca un tema que se le pasa por parametro para comprobar si existe
     * @param string $tema tema a buscar
     * @return mixed
     */
    public function selectTema($tema){
        $query = "SELECT id FROM tbl_themes where nom = :nom";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":nom", $tema, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * elimina la frase en especifico
     * @param int $id de la farse
     */
    public function deleteFrase($id) {
        $query = "DELETE FROM tbl_phrases WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * actualiza una farse que se busca por id, con nuevos valores
     * @param int $id
     * @param string $texto
     * @param string $aId
     * @param string $tId
     */
    public function updateFrase($id, $texto, $aId, $tId) {
        var_dump($id, $texto);
        
        $query = "UPDATE tbl_phrases
              SET texto = :texto, author_id = :aId, theme_id = :tID, updated_at = NOW()
              WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->bindParam(':aId', $aId, PDO::PARAM_INT);
        $stmt->bindParam(':tID', $tId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * inserta una farse nueva en la bd
     * @param string $texto
     * @param string $autorId
     * @param string $temaId
     */
    public function insertFrase($texto, $autorId, $temaId) {
        $query = "INSERT INTO tbl_phrases (texto, author_id, theme_id, created_at, updated_at)
                  VALUES (:texto, :author_id, :theme_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->bindParam(':author_id', $autorId, PDO::PARAM_INT);
        $stmt->bindParam(':theme_id', $temaId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * devuelve una frase o un conjunto de ellas dependiendo lo que se le entre por parametro
     * @param string $texto
     * @param string $autor
     * @param string $tema
     * @return FraseModel[]
     */
    public function filtrar($texto = '', $autor = '', $tema = '') {
        $query = "SELECT
                p.texto AS texto,
                a.nom AS nombreAutor,
                t.nom AS nombreTema,
                p.created_at AS creacion,
                p.updated_at AS updated
            FROM
                tbl_phrases p
            LEFT JOIN
                tbl_authors a ON p.author_id = a.id
            LEFT JOIN
                tbl_themes t ON p.theme_id = t.id
            WHERE
                (:texto = '' OR p.texto RLIKE :texto)
                AND (:autor = '' OR a.nom RLIKE :autor)
                AND (:tema = '' OR t.nom RLIKE :tema)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':texto', $texto, PDO::PARAM_STR);
        $stmt->bindParam(':autor', $autor, PDO::PARAM_STR);
        $stmt->bindParam(':tema', $tema, PDO::PARAM_STR);
        $stmt->execute();
        
        $frases = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $frase = new FraseModel(
                null,
                $row->texto,
                $row->nombreAutor,
                $row->nombreTema,
                $row->creacion,
                $row->updated
                );
            $frases[] = $frase;
        }
        
        return $frases;
    }
}
