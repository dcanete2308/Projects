<?php
class DaoAutor
{
    private $db;
    
    public function __construct() {
        $this->db = DataBasePDO::getInstance()->getConnection();
    }
    /**
     * a traves de un left joinn se enseñan todos los autores que hayan, se hace join para enseñar diferentes campos de la bd, despues lo guarda en un obj de negocio
     * @return autorModel[]
     */
    public function selectAll() {
        $query = "SELECT DISTINCT
                a.nom AS nombre,
                a.id AS autorId,
                a.descripcio AS descripcion,
                a.url AS url,
                COUNT(p.id) AS frases
              FROM
                tbl_authors a
              LEFT JOIN
                tbl_phrases p ON p.author_id = a.id
              GROUP BY
                a.id
              ORDER BY
                a.nom ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $autores = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $autor = new autorModel(
                $row->nombre,
                $row->descripcion,
                $row->url,
                $row->frases,
                $row->autorId
                );
            $autores[] = $autor;
        }
        
        return $autores;
    }
    
    /**
     * hace un query como la del showAll pero busca uno en especifico y no todos
     * @param int $id deñ autor en especifico
     * @return autorModel|NULL objeto de negocio con los datos que nos devuelve el select
     */
    public function selectById($id) {
        $query = "SELECT
            a.nom AS nombre,
            a.id AS autorId,
            a.descripcio AS descripcion,
            a.url AS url,
            COUNT(p.id) AS frases
          FROM
            tbl_authors a
          LEFT JOIN
            tbl_phrases p ON p.author_id = a.id
          WHERE
            a.id = :id";
        
        if (is_array($id)) {
            $id = $id['id'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($row) {
            return new autorModel($row->nombre, $row->descripcion, $row->url, $row->frases, $row->autorId);
        } else {
            return null;
        }
    }
    
    /**
     * seleciona todos los de la pagina en espcifico
     * @param int $limite donde empieza
     * @param int $offset los que coge
     * @return autorModel[]
     */
    public function selectAllLimit($limite, $offset) {
        $query = "SELECT DISTINCT
                a.nom AS nombre,
                a.id AS autorId,
                a.descripcio AS descripcion,
                a.url AS url,
                COUNT(p.id) AS frases
              FROM
                tbl_authors a
              LEFT JOIN
                tbl_phrases p ON p.author_id = a.id
              GROUP BY
                a.id
              ORDER BY
                a.nom ASC
              LIMIT :lim 
              OFFSET :of";
       
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lim', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':of', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $autores = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $autor = new autorModel(
                $row->nombre,
                $row->descripcion,
                $row->url,
                $row->frases,
                $row->autorId
                );
            $autores[] = $autor;
        }
        
        return $autores;
    }
    
    /**
     * devuelve toda la bd
     * @return array resultado
     */
    public function selectAllAuthors() {
        $query = "SELECT nom FROM tbl_authors";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * busca un autor por su nombre
     * @param string $nombre a buscar
     */
    public function selectByName($nombre) {
        $query = "SELECT id FROM tbl_authors WHERE nom = :nombre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    
    /**
     * elimina el autor por su nombre
     * @param int $id del autor
     */
    public function deleteAuthor($id) {
        $query = "DELETE FROM tbl_authors WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * hace un update de un autor de un id en especifico
     * @param int $id
     * @param string $nom
     * @param string $desc
     * @param string $url
     */
    public function updateAuthor($id, $nom, $desc, $url) {
        $query = "UPDATE tbl_authors SET nom = :nom, `descripcio` = :descripcio, url = :url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':descripcio', $desc, PDO::PARAM_STR);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * inserta en la bd el autor
     * @param string $nom del autor
     * @param string $desc del autor
     * @param string $url del autor
     */
    public function insertAutor($nom, $desc, $url){
        if (empty($desc)) {
            $desc ="";
        }
        
        if (empty($url)){
            $url="";
        }
        
        $query = "INSERT INTO tbl_authors (nom, descripcio, url) VALUES (:nom, :descripcio, :url)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
        $stmt->bindParam(":descripcio", $desc, PDO::PARAM_STR);
        $stmt->bindParam(":url", $url, PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * filtra con un rlike el autor que se le pasa por input
     * @param string $autor
     * @param string $descripcion
     * @return autorModel[] obj de negocio
     */
    public function filtrar($autor = '', $descripcion = '') {
        $query = "SELECT DISTINCT
                a.nom AS nombre,
                a.id AS autorId,
                a.descripcio AS descripcion,
                a.url AS url,
                COUNT(p.id) AS frases
              FROM
                tbl_authors a
              LEFT JOIN
                tbl_phrases p ON p.author_id = a.id
              WHERE
                (:autor = '' OR a.nom RLIKE :autor)
                AND (:descripcion = '' OR a.descripcio RLIKE :descripcion)
              GROUP BY
                a.id
              ORDER BY
                a.nom ASC";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':autor', $autor, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        
        $stmt->execute();
        
        $autores = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $autor = new autorModel(
                $row->nombre,
                $row->descripcion,
                $row->url,
                $row->frases,
                $row->autorId
                );
            $autores[] = $autor;
        }
        
        return $autores;
    }
    
}

