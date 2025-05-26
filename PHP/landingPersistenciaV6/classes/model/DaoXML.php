<?php

class DaoXML
{
    
    private $db;
    
    public function __construct()
    {
        $this->db = DataBasePDO::getInstance()->getConnection();
    }
    
    /**
     * crea de nuevo toda la bd, primero la borra y luego la crea
     */
    public function recargar()
    {
        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
            
            $this->db->exec("DROP DATABASE IF EXISTS frases_canete_didac;");
            
            $this->db->exec("CREATE DATABASE IF NOT EXISTS frases_canete_didac;");
            
            $this->db->exec("USE frases_canete_didac;");
            
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS tbl_themes (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    nom VARCHAR(100) NOT NULL,
                    descripcio TEXT NOT NULL
                );
            ");
            
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS tbl_authors (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    nom VARCHAR(100) NOT NULL,
                    descripcio TEXT NOT NULL,
                    url VARCHAR(100) NOT NULL
                );
            ");
            
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS tbl_phrases (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    texto TEXT NOT NULL,
                    author_id INT NOT NULL,
                    theme_id INT NOT NULL,
                    created_at DATETIME NOT NULL,
                    updated_at DATETIME NOT NULL,
                    FOREIGN KEY (author_id) REFERENCES tbl_authors(id) ON DELETE CASCADE,
                    FOREIGN KEY (theme_id) REFERENCES tbl_themes(id) ON DELETE CASCADE
                );
            ");
            
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (PDOException $e) {
            echo "Error al recargar la base de datos: " . $e->getMessage();
        }
    }
    
    /**
     * lee el xml que le entra por el controlador, una vez con eso busca el autor, la frase y el tema, si no esta repetido se hace un isert y en caso que lo este se ignora
     * @param string $ArchivoXml con todos los datos que queremos insertar
     */
    public function cargarDatosXml($ArchivoXml)
    {
        try {
            $xml = simplexml_load_file($ArchivoXml);
            if ($xml === false) {
                throw new Exception("Error al cargar el archivo XML.");
            }
            
            foreach ($xml->autor as $a) {
                $nombre = $a->nombre;
                $desc = $a->descripcion;
                $url = $a['url'];
                
                $stmt = $this->db->prepare("SELECT id FROM tbl_authors WHERE nom = :nom");
                $stmt->bindParam(':nom', $nombre);
                $stmt->execute();
                $autorExistente = $stmt->fetch(PDO::FETCH_OBJ);
                
                if (!$autorExistente) {
                    $stmt = $this->db->prepare("INSERT INTO tbl_authors (nom, descripcio, url) VALUES (:nom, :descripcio, :url)");
                    $stmt->bindParam(':nom', $nombre);
                    $stmt->bindParam(':descripcio', $desc);
                    $stmt->bindParam(':url', $url);
                    $stmt->execute();
                    $authorId = $this->db->lastInsertId();
                } else {
                    $authorId = $autorExistente->id;
                }
                
                foreach ($a->frases->frase as $f) {
                    $texto = $f->texto;
                    $tema = $f->tema;
                    
                    $stmt = $this->db->prepare("SELECT id FROM tbl_themes WHERE nom = :nom");
                    $stmt->bindParam(':nom', $tema);
                    $stmt->execute();
                    $temaExistente = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if (!$temaExistente) {
                        $stmt = $this->db->prepare("INSERT INTO tbl_themes (nom, descripcio) VALUES (:nom, :descripcio)");
                        $stmt->bindParam(':nom', $tema);
                        $stmt->bindParam(':descripcio', $tema);
                        $stmt->execute();
                        $themeId = $this->db->lastInsertId();
                    } else {
                        $themeId = $temaExistente->id;
                    }
                    
                    $stmt = $this->db->prepare("SELECT id FROM tbl_phrases WHERE texto = :texto AND author_id = :author_id AND theme_id = :theme_id");
                    $stmt->bindParam(':texto', $texto);
                    $stmt->bindParam(':author_id', $authorId);
                    $stmt->bindParam(':theme_id', $themeId);
                    $stmt->execute();
                    $fraseExistente = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if (!$fraseExistente) {
                        $stmt = $this->db->prepare("INSERT INTO tbl_phrases (texto, author_id, theme_id, created_at, updated_at) VALUES (:texto, :author_id, :theme_id, NOW(), NOW())");
                        $stmt->bindParam(':texto', $texto);
                        $stmt->bindParam(':author_id', $authorId);
                        $stmt->bindParam(':theme_id', $themeId);
                        $stmt->execute();
                    }
                }
            }
        } catch (PDOException $e) {
            echo "Error al cargar los datos desde el XML: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
}