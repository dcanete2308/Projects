<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
class DataBase {
    private $sgbd = "mysql";
    private static $configuracio;
    private static $_instance;
    private static $link;
    
    private function __construct() {
        self::$configuracio = Config::getInstance();
        
        switch ($this->sgbd) {
            case "mysql":
                self::$link = new mysqli(
                self::$configuracio->__get('host'),
                self::$configuracio->__get('username'),
                self::$configuracio->__get('password'),
                self::$configuracio->__get('db_name')
                );
                break;
                
            case "sqlServer":
                self::$link = sqlsrv_connect(self::$configuracio->__get('host'));
                break;
        }
    }
    
    private function connect() {
        self::$link = new mysqli(
            self::$configuracio->__get('host'),
            self::$configuracio->__get('username'),
            self::$configuracio->__get('password'),
            self::$configuracio->__get('db_name')
            );
    }
    
    private function disconnect() {
        self::$link->close();
    }
    
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function execute($params = [], $types, $query) {
        $this->connect();
        $stmt = self::$link->prepare($query);
        
        if (!empty($params)) {
            if (empty($types)) {
                $types = str_repeat('s', count($params));
            }
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if (stripos($query, "select") !== false) {
            return $stmt->get_result();
        }
    }
}

