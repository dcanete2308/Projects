<?php
namespace Entradas\Controller;

use Entradas\Repository\UsuariRepository;
use Entradas\Entity\Usuari;

class UsuariController
{
    private $entityManager;
    private $usuariRepository;
    
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        $this->usuariRepository = new UsuariRepository(
            $entityManager,
            $entityManager->getClassMetadata(Usuari::class)
            );
    }
    
    public function crearUsuari()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        $required = ['name', 'email', 'phone'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo requerido: $field"]);
                return;
            }
        }
        
        $usuari = new Usuari();
        $usuari->setName($data['name'])
        ->setEmail($data['email'])
        ->setPhone($data['phone'])
        ->setCreatedAt(new \DateTime());
        
        $this->usuariRepository->add($usuari);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Usuario creado',
            'id' => $usuari->getId()
        ]);
    }
    
    public function obtenirUsuari($id)
    {
        $usuari = $this->usuariRepository->findUsuariById($id);
        
        if (!$usuari) {
            http_response_code(404);
            echo json_encode(['error' => 'No se encontro al usuario']);
            return;
        }
        
        $data = [
            'id' => $usuari->getId(),
            'name' => $usuari->getName(),
            'email' => $usuari->getEmail(),
            'phone' => $usuari->getPhone(),
            'createdAt' => $usuari->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
        
        echo json_encode($data);
    }
    
    public function actualitzarUsuari($id)
    {
        $usuari = $this->usuariRepository->findUsuariById($id);
        if (!$usuari) {
            http_response_code(404);
            echo json_encode(['error' => 'No se ha encontrado al usuario']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos JSON inválidos']);
            return;
        }
        
        if (isset($data['name'])) {
            $usuari->setName($data['name']);
        }
        if (isset($data['email'])) {
            $usuari->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $usuari->setPhone($data['phone']);
        }
        if (isset($data['createdAt'])) {
            try {
                $usuari->setCreatedAt(new \DateTime($data['createdAt']));
            } catch (\Exception $e) {
            }
        }
        
        $this->usuariRepository->update($usuari);
        
        echo json_encode([
            'message' => 'Usuario actualizado',
            'id' => $usuari->getId()
        ]);
    }
    
    public function eliminarUsuari($id)
    {
        $usuari = $this->usuariRepository->findUsuariById($id);
        
        if (!$usuari) {
            http_response_code(404);
            echo json_encode(['error' => 'No se ha encontrado al usuario']);
            return;
        }
        
        $this->usuariRepository->delete($usuari);
        
        echo json_encode(['message' => 'Usuario eliminado']);
    }
}