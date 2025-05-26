<?php
namespace Entradas\Controller;

use Entradas\Entity\Seient;
use Entradas\Entity\Localitzacio;
use Entradas\Repository\SeientRepository;
use Entradas\Repository\LocalitzacioRepository;

class SeientController
{
    private $entityManager;
    private $seientRepository;
    private $localitzacioRepository;
    
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        
        $this->seientRepository = new SeientRepository(
            $entityManager,
            $entityManager->getClassMetadata(Seient::class)
            );
        
        $this->localitzacioRepository = new LocalitzacioRepository(
            $entityManager,
            $entityManager->getClassMetadata(Localitzacio::class)
            );
    }
    
    public function crearSeient()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        $required = ['row', 'number', 'type', 'venueId'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo requerido: $field"]);
                return;
            }
        }
        
        $venue = $this->localitzacioRepository->find($data['venueId']);
        if (!$venue) {
            http_response_code(404);
            echo json_encode(['error' => 'Venue (Localitzacio) no encontrado']);
            return;
        }
        
        $seient = new Seient();
        $seient->setRow($data['row'])
        ->setNumber((int)$data['number'])
        ->setType($data['type'])
        ->setVenue($venue);
        
        $this->seientRepository->add($seient);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Seient creado',
            'id' => $seient->getId()
        ]);
    }
    
    public function obtenirSeient($id)
    {
        $seient = $this->seientRepository->get($id);
        
        if (!$seient) {
            http_response_code(404);
            echo json_encode(['error' => 'Seient no encontrado']);
            return;
        }
        
        $data = [
            'id' => $seient->getId(),
            'row' => $seient->getRow(),
            'number' => $seient->getNumber(),
            'type' => $seient->getType(),
            'venueId' => $seient->getVenue()->getId(),
        ];
        
        echo json_encode($data);
    }
    
    public function actualitzarSeient($id)
    {
        $seient = $this->seientRepository->find($id);
        if (!$seient) {
            http_response_code(404);
            echo json_encode(['error' => 'Seient no encontrado']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        if (isset($data['row'])) {
            $seient->setRow($data['row']);
        }
        if (isset($data['number'])) {
            $seient->setNumber((int)$data['number']);
        }
        if (isset($data['type'])) {
            $seient->setType($data['type']);
        }
        if (isset($data['venueId'])) {
            $venue = $this->localitzacioRepository->find($data['venueId']);
            if (!$venue) {
                http_response_code(404);
                echo json_encode(['error' => 'Venue (Localitzacio) no encontrado']);
                return;
            }
            $seient->setVenue($venue);
        }
        
        $this->seientRepository->update($seient);
        
        echo json_encode([
            'message' => 'Seient actualizado',
            'id' => $seient->getId()
        ]);
    }
    
    public function eliminarSeient($id)
    {
        $seient = $this->seientRepository->find($id);
        
        if (!$seient) {
            http_response_code(404);
            echo json_encode(['error' => 'Seient no encontrado']);
            return;
        }
        
        $this->seientRepository->delete($seient);
        
        echo json_encode(['message' => 'Seient eliminado']);
    }
}
