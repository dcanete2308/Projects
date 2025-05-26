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

class EntradaController
{

    private $entityManager;

    private $ticketRepository;

    private $eventRepository;

    private $seatRepository;

    private $compraRepository;

    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;

        $this->ticketRepository = new TicketRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Ticket::class));
        $this->eventRepository = new EventRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Esdeveniment::class));
        $this->compraRepository = new CompraRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Compra::class));
        $this->seatRepository = new SeientRepository($entityManager, $entityManager->getClassMetadata(\Entradas\Entity\Seient::class));
    }

    public function generarPDF($referencia)
    {
        $entrada = $this->ticketRepository->findByReferencia($referencia);

        if (! $entrada) {
            $vistaError = new ErrorView();
            $vistaError->showError();
            return;
        }

        $vista = new TicketView();
        $vista->showTicket($entrada);
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

    public function crearTicket()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (! $data) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Datos JSON inválidos'
            ]);
            return;
        }

        $required = [
            'code',
            'price',
            'status',
            'img',
            'event_id',
            'seat_id',
            'purchase_id'
        ];
        
        foreach ($required as $field) {
            if (! isset($data[$field])) {
                http_response_code(400);
                echo json_encode([
                    'error' => "Falta el campo requerido: $field"
                ]);
                return;
            }
        }

        $event = $this->eventRepository->findEventoById($data['event_id']);
        if (! $event) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Evento no encontrado con ID: ' . $data['event_id']
            ]);
            return;
        }

        $seat = $this->seatRepository->find($data['seat_id']);
        if (! $seat) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Asiento no encontrado con ID: ' . $data['seat_id']
            ]);
            return;
        }
        
        $ticketExistente = $this->ticketRepository->findAsiento($seat, $event);
                
        if ($ticketExistente) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Este asiento ya tiene un ticket asignado para este evento.'
            ]);
            return;
        }

        $purchase = $this->compraRepository->findCompra($data['purchase_id']);
        if (! $purchase) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Compra no encontrada con ID: ' . $data['purchase_id']
            ]);
            return;
        }

        $ticket = new Ticket();
        $ticket->setCode($data['code'])
            ->setPrice($data['price'])
            ->setStatus($data['status'])
            ->setImg($data['img'])
            ->setEvent($event)
            ->setSeat($seat)
            ->setPurchase($purchase);

        $this->ticketRepository->add($ticket);

        http_response_code(201);
        echo json_encode([
            'message' => 'Ticket creado correctamente',
            'id' => $ticket->getId()
        ]);
    }

    public function actualitzarTicket($id)
    {
        $ticket = $this->ticketRepository->find($id);

        if (! $ticket) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Ticket no trobat'
            ]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['code'])) {
            $ticket->setCode($data['code']);
        }
        if (isset($data['status'])) {
            $ticket->setStatus($data['status']);
        }
        if (isset($data['price'])) {
            $ticket->setPrice((float) $data['price']);
        }
        if (isset($data['img'])) {
            $ticket->setImg($data['img']);
        }
        if (isset($data['event_id'])) {
            $event = $this->entityManager->getReference(\Entradas\Entity\Esdeveniment::class, $data['event_id']);
            $ticket->setEvent($event);
        }
        if (isset($data['seat_id'])) {
            $seat = $this->entityManager->getReference(\Entradas\Entity\Seient::class, $data['seat_id']);
            $ticket->setSeat($seat);
        }
        if (isset($data['purchase_id'])) {
            $purchase = $this->entityManager->getReference(\Entradas\Entity\Compra::class, $data['purchase_id']);
            $ticket->setPurchase($purchase);
        }

        $ticketActualitzat = $this->ticketRepository->update($ticket);

        echo json_encode([
            'missatge' => 'Ticket actualitzat correctament',
            'id' => $ticketActualitzat->getId(),
            'code' => $ticketActualitzat->getCode(),
            'status' => $ticketActualitzat->getStatus(),
            'price' => $ticketActualitzat->getPrice()
        ]);
    }

    public function eliminarTicket($id)
    {
        $ticket = $this->ticketRepository->find($id);

        if (! $ticket) {
            http_response_code(404);
            echo json_encode([
                'error' => 'Ticket no trobat'
            ]);
            return;
        }

        $this->ticketRepository->delete($ticket);

        echo json_encode([
            'missatge' => 'Ticket eliminat correctament'
        ]);
    }
}
