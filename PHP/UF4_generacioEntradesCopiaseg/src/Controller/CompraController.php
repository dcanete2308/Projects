<?php
namespace Entradas\Controller;

use Entradas\Entity\Compra;
use Entradas\Entity\Usuari;
use Entradas\Repository\CompraRepository;
use Entradas\Repository\UsuariRepository;

class CompraController
{
    private $entityManager;
    private $compraRepository;
    private $usuariRepository;
    
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        
        $this->compraRepository = new CompraRepository(
            $entityManager,
            $entityManager->getClassMetadata(Compra::class)
            );
        
        $this->usuariRepository = new UsuariRepository(
            $entityManager,
            $entityManager->getClassMetadata(Usuari::class)
            );
    }
    
    public function crearCompra()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        $required = ['purchaseDate', 'paymentMethod', 'totalAmount', 'userId'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo requerido: $field"]);
                return;
            }
        }
        
        $usuari = $this->usuariRepository->find($data['userId']);
        if (!$usuari) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuari no encontrado']);
            return;
        }
        
        $compra = new Compra();
        $compra->setPurchaseDate(new \DateTime($data['purchaseDate']))
        ->setPaymentMethod($data['paymentMethod'])
        ->setTotalAmount($data['totalAmount'])
        ->setUser($usuari);
        
        $this->compraRepository->add($compra);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Compra creada',
            'id' => $compra->getId()
        ]);
    }
    
    public function obtenirCompra($id)
    {
        $compra = $this->compraRepository->findCompra($id);
        
        if (!$compra) {
            http_response_code(404);
            echo json_encode(['error' => 'Compra no encontrada']);
            return;
        }
        
        $data = [
            'id' => $compra->getId(),
            'purchaseDate' => $compra->getPurchaseDate()->format('Y-m-d H:i:s'),
            'paymentMethod' => $compra->getPaymentMethod(),
            'totalAmount' => $compra->getTotalAmount(),
            'userId' => $compra->getUser()->getId()
        ];
        
        echo json_encode($data);
    }
    
    public function actualitzarCompra($id)
    {
        $compra = $this->compraRepository->findCompra($id);
        
        if (!$compra) {
            http_response_code(404);
            echo json_encode(['error' => 'Compra no encontrada']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        if (isset($data['userId'])) {
            $usuari = $this->usuariRepository->find($data['userId']);
            if (!$usuari) {
                http_response_code(404);
                echo json_encode(['error' => 'Usuari no encontrado']);
                return;
            }
            $compra->setUser($usuari);
        }
        
        if (isset($data['purchaseDate'])) {
            try {
                $fecha = new \DateTime($data['purchaseDate']);
                $compra->setPurchaseDate($fecha);
            } catch (\Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Formato de fecha inválido']);
                return;
            }
        }
        
        if (isset($data['paymentMethod'])) {
            $compra->setPaymentMethod($data['paymentMethod']);
        }
        
        if (isset($data['totalAmount'])) {
            $compra->setTotalAmount($data['totalAmount']);
        }
        
        $this->compraRepository->actualizarCompra($compra);
        
        echo json_encode(['message' => 'Compra actualizada']);
    }
    
    
    public function eliminarCompra($id)
    {
        $compra = $this->compraRepository->findCompra($id);
        
        if (!$compra) {
            http_response_code(404);
            echo json_encode(['error' => 'Compra no encontrada']);
            return;
        }
        
        $this->compraRepository->delete($compra);
        
        echo json_encode(['message' => 'Compra eliminada']);
    }
}
