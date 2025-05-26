<?php
namespace Entradas\Controller;

use Entradas\Repository\TicketRepository;
use Entradas\Repository\EventRepository;
use Entradas\Repository\SeientRepository;
use Entradas\Repository\CompraRepository;
use Entradas\Vista\ErrorView;
use Entradas\Entity\Ticket;
use Entradas\Vista\TicketView;
use Entradas\Entity\Seient;
use Entradas\Repository\LocalitzacioRepository;
use Entradas\Entity\Esdeveniment;

class EsdevenimentController
{

    private $entityManager;

    private $eventRepository;
    
    private $ticketRepository;
    
    private $localitzacioRepository;
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;

        $this->eventRepository = new EventRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Esdeveniment::class));
        $this->localitzacioRepository = new LocalitzacioRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Localitzacio::class));
        $this->ticketRepository = new TicketRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Ticket::class));
    }

    
    public function generarXml($fecha)
    {
        if (! $fecha || ! \DateTime::createFromFormat('Y-m-d', $fecha)) {
            die("Format de data incorrecte. Usa YYYY-MM-DD.");
        }

        $eventos = $this->ticketRepository->findEventsByDate($fecha);

        header('Content-Type: application/xml; charset=utf-8');

        $xml = new \SimpleXMLElement('<espectacles/>');

        if (empty($eventos)) {
            $xml->addChild('alerta', 'No hi ha espectacles per a la data proporcionada.');
        } else {
            foreach ($eventos as $evento) {
                $eventoXml = $xml->addChild('espectacle');
                $eventoXml->addChild('nom', htmlspecialchars($evento->getTitle()));
                $eventoXml->addChild('descripcio', htmlspecialchars($evento->getDescription()));
                $eventoXml->addChild('dataInici', $evento->getStartTime()
                    ->format('Y-m-d H:i'));
                $eventoXml->addChild('dataFi', $evento->getEndTime()
                    ->format('Y-m-d H:i'));
                $eventoXml->addChild('tipus', htmlspecialchars($evento->getType()));

                $venue = $evento->getVenue();
                if ($venue) {
                    $eventoXml->addChild('lloc', htmlspecialchars($venue->getName() ?? ''));
                    $eventoXml->addChild('adreça', htmlspecialchars($venue->getAddress() ?? ''));
                    $eventoXml->addChild('ciutat', htmlspecialchars($venue->getCity() ?? ''));
                } else {
                    $eventoXml->addChild('lloc', '');
                    $eventoXml->addChild('adreça', '');
                    $eventoXml->addChild('ciutat', '');
                }
            }
        }

        echo $xml->asXML();
    }

    public function crearEsdeveniment()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (! $data) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Datos JSON inválidos'
            ]);
            return;
        }
        
        $required = ['title','description','start_time','end_time','type','venue_id',];
        
        foreach ($required as $field) {
            if (! isset($data[$field])) {
                http_response_code(400);
                echo json_encode([
                    'error' => "Falta el campo requerido: $field"
                ]);
                return;
            }
        }
        
        $localitzacio = $this->localitzacioRepository->findLocalitzacioById($data['venue_id']);
        if (! $localitzacio) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Localitzacio no encontrada con ID: ' . $data['venue_id']
            ]);
            return;
        }
        
        $esdeveniment = new Esdeveniment();
        $esdeveniment->setTitle($data['title'])
        ->setDescription($data['description'])
        ->setStartTime(new \DateTime($data['start_time']))
        ->setEndTime(new \DateTime($data['end_time']))
        ->setType($data['type'])
        ->setVenue($localitzacio);
        
        $this->eventRepository->add($esdeveniment);
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Esdeveniment creado correctamente',
            'id' => $esdeveniment->getId()
        ]);
    }
    

    public function actualitzarEsdeveniment($id)
    {
        $esdeveniment = $this->eventRepository->findEventoById($id);
        
        if (! $esdeveniment) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Esdeveniment no trobat'
            ]);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['title'])) {
            $esdeveniment->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $esdeveniment->setDescription($data['description']);
        }
        if (isset($data['start_time'])) {
            $esdeveniment->setStartTime(new \DateTime($data['start_time']));
        }
        if (isset($data['end_time'])) {
            $esdeveniment->setEndTime(new \DateTime($data['end_time']));
        }
        if (isset($data['type'])) {
            $esdeveniment->setType($data['type']);
        }
        if (isset($data['venue_id'])) {
            $venue = $this->entityManager->getReference(\Entradas\Entity\Localitzacio::class, $data['venue_id']);
            $esdeveniment->setVenue($venue);
        }
        
        $this->entityManager->flush();
        
        echo json_encode([
            'missatge' => 'Esdeveniment actualitzat correctament',
            'id' => $esdeveniment->getId(),
            'title' => $esdeveniment->getTitle(),
            'start_time' => $esdeveniment->getStartTime()->format('Y-m-d H:i:s'),
            'end_time' => $esdeveniment->getEndTime()->format('Y-m-d H:i:s'),
            'type' => $esdeveniment->getType()
        ]);
    }
    

    public function eliminarEsdeveniment($id)
    {
        $esdeveniment = $this->eventRepository->findEventoById($id);
        
        if (! $esdeveniment) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Esdeveniment no trobat'
            ]);
            return;
        }
        
        $this->eventRepository->delete($esdeveniment);
        
        echo json_encode([
            'missatge' => 'Esdeveniment eliminat correctament'
        ]);
    }
        
}
