<?php
session_start();
use Xiringuito\Controller\FrontController;
use Xiringuito\Entity\Rol;
define("__ROOT__", __DIR__ . "/../");
error_reporting(E_ALL);
ini_set("display_errors", 1);
require '../vendor/autoload.php';
require_once '../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + 60*60*24*30, '/');
    
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

try {
    \Xiringuito\Controller\FrontController::dispatch();
} catch(Exception $e) {
    var_dump("Error en FrontController: " . $e->getMessage());
    throw new Exception('Hi ha hagut un error: '.$e->getMessage());
}
