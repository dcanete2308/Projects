<?php
namespace Entradas\Core;

use Entradas\Controller\CompraController;
use Entradas\Controller\EntradaController;
use Entradas\Controller\EsdevenimentController;
use Entradas\Controller\LocalitzacioController;
use Entradas\Controller\SeientController;
use Entradas\Controller\UsuariController;

class FrontController
{

    public function handleRequest()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'];

        if (strpos($requestUri, $scriptName . '/api/') === 0) {
            $basePath = $scriptName;
            $this->handleApiRequest($requestUri, $basePath);
        } elseif (strpos($requestUri, $scriptName) === 0 || $requestUri === dirname($scriptName) . '/') {
            $this->handleTraditionalRequest();
        } else {
            http_response_code(404);
            echo json_encode([
                'error' => 'Endpoint no encontrado'
            ]);
        }
    }

    private function handleTraditionalRequest()
    {
        $controller = new EntradaController();

        if (isset($_GET['ref'])) {
            $referencia = $this->sanitize($_GET['ref']);
            $controller->generarPDF($referencia);
        } elseif (isset($_GET['data'])) {
            $fecha = $this->sanitize($_GET['data']);
            $controller->generarXml($fecha);
        } else {
            http_response_code(400);
            echo "Parámetros incorrectos. Use ?ref=REFERENCIA o ?data=FECHA (YYYY-MM-DD)";
        }
    }

    private function handleApiRequest($requestUri, $basePath)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = str_replace($basePath . '/api/', '', $requestUri);
        $parts = explode('/', trim($path, '/'));

        $resource = $parts[0] ?? null;
        $id = $parts[1] ?? null;

        switch ($resource) {
            case 'tickets':
                $controller = new EntradaController();
                $this->dispatchTicket($controller, $method, $id);
                break;

            case 'esdeveniments':
                $controller = new EsdevenimentController();
                $this->dispatchEsdeveniment($controller, $method, $id);
                break;

            case 'usuaris':
                $controller = new UsuariController();
                $this->dispatchUsuari($controller, $method, $id);
                break;
               
            case 'seients':
                $controller = new SeientController();
                $this->dispatchSeient($controller, $method, $id);
                break;
    
            case 'compra':
                $controller = new CompraController();
                $this->dispatchCompra($controller, $method, $id);
                break;
                
            case 'localitzacio':
                $controller = new LocalitzacioController();
                $this->dispatchLocalitzacio($controller, $method, $id);
                break;
                
            default:
                http_response_code(404);
                echo json_encode([
                    'error' => 'Recurso API no encontrado'
                ]);
                break;
        }
    }

    private function dispatchTicket($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                $pdf = $_GET['ref'] ?? null;
                $controller->generarPDF($pdf);
                break;

            case 'POST':
                $controller->crearTicket();
                break;

            case 'PUT':
                if ($id) {
                    $controller->actualitzarTicket($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID de ticket requerido'
                    ]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $controller->eliminarTicket($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID de ticket requerido'
                    ]);
                }
                break;

            default:
                http_response_code(405);
                header('Allow: POST, PUT, DELETE');
                echo json_encode([
                    'error' => 'Método no permitido'
                ]);
                break;
        }
    }

    private function dispatchEsdeveniment($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                $fecha = $_GET['data'] ?? null;
                $controller->generarXml($fecha);
                break;

            case 'POST':
                $controller->crearEsdeveniment();
                break;

            case 'PUT':
                if ($id) {
                    $controller->actualitzarEsdeveniment($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID requerido'
                    ]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $controller->eliminarEsdeveniment($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID requerido'
                    ]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode([
                    'error' => 'Método no permitido'
                ]);
                break;
        }
    }

    private function dispatchUsuari($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->obtenirUsuari($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID de usuario requerido'
                    ]);
                }
                break;

            case 'POST':
                $controller->crearUsuari();
                break;

            case 'PUT':
                if ($id) {
                    $controller->actualitzarUsuari($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID de usuario requerido'
                    ]);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $controller->eliminarUsuari($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'ID de usuario requerido'
                    ]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode([
                    'error' => 'Método no permitido'
                ]);
                break;
        }
    }
    
    private function dispatchSeient($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->obtenirSeient($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de seient requerido']);
                }
                break;
                
            case 'POST':
                $controller->crearSeient();
                break;
                
            case 'PUT':
                if ($id) {
                    $controller->actualitzarSeient($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de seient requerido']);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $controller->eliminarSeient($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de seient requerido']);
                }
                break;
                
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
    }
    
    private function dispatchCompra($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->obtenirCompra($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de compra requerido']);
                }
                break;
                
            case 'POST':
                $controller->crearCompra();
                break;
                
            case 'PUT':
                if ($id) {
                    $controller->actualitzarCompra($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de compra requerido']);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $controller->eliminarCompra($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de compra requerido']);
                }
                break;
                
            default:
                http_response_code(405);
                header('Allow: GET, POST, PUT, DELETE');
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
    }
    
    private function dispatchLocalitzacio($controller, $method, $id)
    {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->obtenirLocalitzacio($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de localización requerido']);
                }
                break;
                
            case 'POST':
                $controller->crearLocalitzacio();
                break;
                
            case 'PUT':
                if ($id) {
                    $controller->actualitzarLocalitzacio($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de localización requerido']);
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $controller->eliminarLocalitzacio($this->sanitize($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de localización requerido']);
                }
                break;
                
            default:
                http_response_code(405);
                header('Allow: GET, POST, PUT, DELETE');
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
    }
        
    private function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
