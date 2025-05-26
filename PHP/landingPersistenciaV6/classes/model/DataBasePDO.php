<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
class DataBasePDO
{
    //he creado un dataBase para no repetir lo mismo en 3 ficheros distintos, así se aplica el dry y es mas optimo
    private $sgbd = "mysql";
    private static $configuracio;
    private static $_instance;
    private static $link;
    
    private function __construct()
    {
        self::$configuracio = ConfigPDO::getInstance();
        $dsn = "mysql:host=" . self::$configuracio->__get('host') . ";dbname=" . self::$configuracio->__get('db_name') . ";charset=utf8";
        self::$link = new PDO($dsn, self::$configuracio->__get('username'), self::$configuracio->__get('password'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }

    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function getConnection()
    {
        return self::$link;
    }
    
}

