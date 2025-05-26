<?php
namespace Xiringuito\Controller;
use Doctrine\DBAL\Driver\PDO\Exception;
use Xiringuito\Core\Http;

class FrontController {
    private const DEFAULT_CONTROLLER = "Home";
    private const DEFAULT_ACTION = "show";
    
    public static function dispatch() {
        $controller_name = self::DEFAULT_CONTROLLER;
        $action = self::DEFAULT_ACTION;
        $params = [];
        
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                if (!empty($_GET)) {
                    $keys = array_keys($_GET);
                    
                    if (!(count($keys) === 1 && $keys[0] === 'lang')) {
                        $url = trim($keys[0] ?? '', "/");
                        $url = self::sanitize($url, "url");
                        $urlParts = explode("/", $url);
                        
                        if (!empty($urlParts[0])) {
                            $controller_name = ucwords($urlParts[0]);
                        }
                        
                        if (!empty($urlParts[1])) {
                            $action = $urlParts[1];
                        }
                        
                        if (count($urlParts) > 2) {
                            for ($i = 2; $i < count($urlParts); $i++) {
                                $params[] = strtolower($urlParts[$i]);
                            }
                        }
                    }
                }
                
                $http = new Http($controller_name, $action, $params);
                $http->get();
                break;
                
            case "POST":
                $keys = array_keys($_GET);
                if (!empty($keys)) {
                    $url = trim($keys[0], "/");
                    $url = self::sanitize($url, "url");
                    $urlParts = explode("/", $url);
                    
                    if (!empty($urlParts[0])) {
                        $controller_name = ucwords($urlParts[0]);
                    }
                    
                    if (!empty($urlParts[1])) {
                        $action = $urlParts[1];
                    }
                }
                
                $params = $_POST;
                $http = new Http($controller_name, $action, $params);
                $http->post();
                break;
                
            default:
                throw new \Exception("Mètode no suportat");
        }    
    }
    
    public static function sanitize($var, $type = "string") {
        $flags = NULL;
        $var = htmlspecialchars(stripslashes(trim($var)));
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                $output = filter_var($var, $filter);
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                $output = filter_var($var, $filter);
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
                $flags = FILTER_FLAG_EMAIL_UNICODE;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'string':
            default:
                //$filter = FILTER_SANITIZE_STRING; Deprecated
                $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                $output = filter_var($var, $filter, $flags);
                break;
        }
        return ($output);
    }
}

