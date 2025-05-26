<?php

abstract class DBAbstractModel {

    private static $db_host = 'localhost';
    private static $db_user = 'didacAdmin';
    private static $db_pass = 'didac';

    protected $db_name = 'myweb';
    protected $query;
    protected $rows = array();

    private $conn;

    # mètodes abstractes per a ABM de classes que heretin
    abstract protected function get();
    abstract protected function set();
    abstract protected function edit();
    abstract protected function delete();

    # els següents mètodes poden definir-se amb exactitud
    # i no són abstractes
    # Connectar a la base de dades
    private function open_connection() {
        $this->conn = new mysqli(self::$db_host, self::$db_user, self::$db_pass, $this->db_name);
    }

    # Desconectar la base de dades
    private function close_connection() {
        $this->conn->close();
    }

    # Executar un query simple del tipus INSERT, DELETE, UPDATE
    protected function execute_single_query() {
        $this->open_connection();
        $this->conn->query($this->query);
        $this->close_connection();
    }

    # Portar resultats d'una consulta en un Array
    protected function get_results_from_query() {
        $this->open_connection();
        $result = $this->conn->query($this->query);
        while ($this->rows[] = $result->fetch_assoc());
        $result->close();
        $this->close_connection();
        array_pop($this->rows);
    }
}


