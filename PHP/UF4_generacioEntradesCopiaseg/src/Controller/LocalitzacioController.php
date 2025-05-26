<?php
namespace Entradas\Controller;

use Entradas\Entity\Localitzacio;
use Entradas\Repository\LocalitzacioRepository;

class LocalitzacioController
{
    private $entityManager;
    private $localitzacioRepository;
    
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        $this->localitzacioRepository = new LocalitzacioRepository(
            $entityManager,
            $entityManager->getClassMetadata(Localitzacio::class)
            );
    }
    
    public function crearLocalitzacio()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        $required = ['name', 'address', 'city', 'capacity'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo requerido: $field"]);
                return;
            }
        }
        
        $localitzacio = new Localitzacio();
        $localitzacio->setName($data['name'])
        ->setAddress($data['address'])
        ->setCity($data['city'])
        ->setCapacity((int)$data['capacity']);
        
        $this->localitzacioRepository->add($localitzacio);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Localitzacio creada',
            'id' => $localitzacio->getId()
        ]);
    }
    
    public function obtenirLocalitzacio($id)
    {
        $localitzacio = $this->localitzacioRepository->findLocalitzacioById($id);
        if (!$localitzacio) {
            http_response_code(404);
            echo json_encode(['error' => 'Localitzacio no encontrada']);
            return;
        }
        
        $data = [
            'id' => $localitzacio->getId(),
            'name' => $localitzacio->getName(),
            'address' => $localitzacio->getAddress(),
            'city' => $localitzacio->getCity(),
            'capacity' => $localitzacio->getCapacity()
        ];
        
        echo json_encode($data);
    }
    
    public function actualitzarLocalitzacio($id)
    {
        $localitzacio = $this->localitzacioRepository->findLocalitzacioById($id);
        if (!$localitzacio) {
            http_response_code(404);
            echo json_encode(['error' => 'Localitzacio no encontrada']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        if (isset($data['name'])) {
            $localitzacio->setName($data['name']);
        }
        if (isset($data['address'])) {
            $localitzacio->setAddress($data['address']);
        }
        if (isset($data['city'])) {
            $localitzacio->setCity($data['city']);
        }
        if (isset($data['capacity'])) {
            $localitzacio->setCapacity((int)$data['capacity']);
        }
        
        $this->localitzacioRepository->update($localitzacio);
        
        echo json_encode(['message' => 'Localitzacio actualitzada']);
    }
    
    public function eliminarLocalitzacio($id)
    {
        $localitzacio = $this->localitzacioRepository->findLocalitzacioById($id);
        if (!$localitzacio) {
            http_response_code(404);
            echo json_encode(['error' => 'Localitzacio no encontrada']);
            return;
        }
        
        $this->localitzacioRepository->delete($localitzacio);
        
        echo json_encode(['message' => 'Localitzacio eliminada']);
    }
}
