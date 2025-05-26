<?php
use Entradas\Core\FrontController;
use Entradas\Entity\Compra;
use Entradas\Entity\Esdeveniment;
use Entradas\Entity\Localitzacio;
use Entradas\Entity\Seient;
use Entradas\Entity\Ticket;
use Entradas\Entity\Usuari;

error_reporting(E_ALL);
ini_set("display_errors", 1);
require 'vendor/autoload.php';
require_once 'src/bootstrap.php';

$compraRepo = $entityManager->getRepository(Compra::class);
$eventRepo = $entityManager->getRepository(Esdeveniment::class);
$ticketRepo = $entityManager->getRepository(Ticket::class);

$existenTickets = $ticketRepo->findByReferencia("COLDPLAY001") || $ticketRepo->findByReferencia("COLDPLAY002") || $ticketRepo->findByReferencia("BARCA001") || $ticketRepo->findByReferencia("BARCA002");
$existenCompras = $compraRepo->findCompra(1) || $compraRepo->findCompra(2);
$existenEventos = $eventRepo->findEvento("Coldplay en Barcelona") || $eventRepo->findEvento("FC Barcelona vs Real Betis");

if (! $existenTickets && ! $existenCompras && ! $existenEventos) {
    $palauSantJordi = (new Localitzacio())->setName("Palau Sant Jordi")
        ->setAddress("Carrer de l'Arístides Maillol")
        ->setCity("Barcelona")
        ->setCapacity(17000);
    $entityManager->persist($palauSantJordi);

    $campNou = (new Localitzacio())->setName("Spotify Camp Nou")
        ->setAddress("Carrer d'Arístides Maillol, 12")
        ->setCity("Barcelona")
        ->setCapacity(99354);
    $entityManager->persist($campNou);

    $usuari1 = (new Usuari())->setName("Didac Cañete")
        ->setEmail("didac@example.com")
        ->setPhone("600111222")
        ->setCreatedAt(new \DateTime());
    $entityManager->persist($usuari1);

    $usuari2 = (new Usuari())->setName("Toni Aguilar")
        ->setEmail("toni@example.com")
        ->setPhone("600333444")
        ->setCreatedAt(new \DateTime());
    $entityManager->persist($usuari2);

    $eventoColdplay = (new Esdeveniment())->setTitle("Coldplay en Barcelona")
        ->setDescription("Concierto de Coldplay en Barcelona")
        ->setStartTime(new \DateTime("2024-05-15 20:00"))
        ->setEndTime(new \DateTime("2024-05-15 23:00"))
        ->setType("Concierto")
        ->setVenue($palauSantJordi);
    $entityManager->persist($eventoColdplay);

    $eventoBarca = (new Esdeveniment())->setTitle("FC Barcelona vs Real Betis")
        ->setDescription("Partido de liga FC Barcelona vs Real Betis")
        ->setStartTime(new \DateTime("2024-05-15 18:00"))
        ->setEndTime(new \DateTime("2024-05-15 20:00"))
        ->setType("Deportivo")
        ->setVenue($campNou);
    $entityManager->persist($eventoBarca);

    $eventoF1 = (new Esdeveniment())->setTitle("Gran Premio de España de F1")
        ->setDescription("Gran Premio celebrado en Montmeló")
        ->setStartTime(new \DateTime("2024-06-09 14:00"))
        ->setEndTime(new \DateTime("2024-06-09 16:00"))
        ->setType("Deportivo")
        ->setVenue($campNou);
    $entityManager->persist($eventoF1);

    $eventoBadBunny = (new Esdeveniment())->setTitle("Bad Bunny - Tour 'Debí tirar más fotos'")
        ->setDescription("Concierto de Bad Bunny en el Estadi Olímpic")
        ->setStartTime(new \DateTime("2024-07-20 21:00"))
        ->setEndTime(new \DateTime("2024-07-20 23:30"))
        ->setType("Concierto")
        ->setVenue($palauSantJordi);
    $entityManager->persist($eventoBadBunny);

    $compra1 = (new Compra())->setPurchaseDate(new \DateTime())
        ->setPaymentMethod("Tarjeta")
        ->setTotalAmount(150.00)
        ->setUser($usuari1);
    $entityManager->persist($compra1);

    $compra2 = (new Compra())->setPurchaseDate(new \DateTime())
        ->setPaymentMethod("PayPal")
        ->setTotalAmount(90.00)
        ->setUser($usuari2);
    $entityManager->persist($compra2);

    $seient1 = (new Seient())->setRow("A")
        ->setNumber(10)
        ->setType("VIP")
        ->setVenue($palauSantJordi);
    $entityManager->persist($seient1);

    $seient2 = (new Seient())->setRow("C")
        ->setNumber(25)
        ->setType("General")
        ->setVenue($palauSantJordi);
    $entityManager->persist($seient2);

    $seient3 = (new Seient())->setRow("B")
        ->setNumber(15)
        ->setType("Preferente")
        ->setVenue($campNou);
    $entityManager->persist($seient3);

    $seient4 = (new Seient())->setRow("E")
        ->setNumber(20)
        ->setType("General")
        ->setVenue($campNou);
    $entityManager->persist($seient4);

    $seient5 = (new Seient())->setRow("A")
        ->setNumber(5)
        ->setType("Grada Principal")
        ->setVenue($campNou);
    $entityManager->persist($seient5);

    $seient6 = (new Seient())->setRow("C")
        ->setNumber(12)
        ->setType("Grada Lateral")
        ->setVenue($campNou);
    $entityManager->persist($seient6);

    $seient7 = (new Seient())->setRow("A")
        ->setNumber(8)
        ->setType("Platea")
        ->setVenue($palauSantJordi);
    $entityManager->persist($seient7);

    $seient8 = (new Seient())->setRow("D")
        ->setNumber(22)
        ->setType("General")
        ->setVenue($palauSantJordi);
    $entityManager->persist($seient8);

    $ticket1 = (new Ticket())->setCode("COLDPLAY001")
        ->setImg('./src/static/media/coldplay.jpg')
        ->setPrice(150.00)
        ->setStatus("Pagado")
        ->setEvent($eventoColdplay)
        ->setSeat($seient1)
        ->setPurchase($compra1);
    $entityManager->persist($ticket1);

    $ticket2 = (new Ticket())->setCode("COLDPLAY002")
        ->setImg('./src/static/media/coldplay.jpg')
        ->setPrice(90.00)
        ->setStatus("Pagado")
        ->setEvent($eventoColdplay)
        ->setSeat($seient2)
        ->setPurchase($compra2);
    $entityManager->persist($ticket2);

    $ticket3 = (new Ticket())->setCode("BARCA001")
        ->setImg('./src/static/media/barca.jpg')
        ->setPrice(120.00)
        ->setStatus("Pagado")
        ->setEvent($eventoBarca)
        ->setSeat($seient3)
        ->setPurchase($compra1);
    $entityManager->persist($ticket3);

    $ticket4 = (new Ticket())->setCode("BARCA002")
        ->setImg('./src/static/media/barca.jpg')
        ->setPrice(80.00)
        ->setStatus("Pagado")
        ->setEvent($eventoBarca)
        ->setSeat($seient4)
        ->setPurchase($compra2);
    $entityManager->persist($ticket4);

    $ticket5 = (new Ticket())->setCode("F1001")
        ->setImg('./src/static/media/f1.jpg')
        ->setPrice(200.00)
        ->setStatus("Pagado")
        ->setEvent($eventoF1)
        ->setSeat($seient5)
        ->setPurchase($compra1);
    $entityManager->persist($ticket5);

    $ticket6 = (new Ticket())->setCode("F1002")
        ->setImg('./src/static/media/f1.jpg')
        ->setPrice(150.00)
        ->setStatus("Pagado")
        ->setEvent($eventoF1)
        ->setSeat($seient6)
        ->setPurchase($compra2);
    $entityManager->persist($ticket6);

    $ticket7 = (new Ticket())->setCode("BADBUNNY001")
        ->setImg('./src/static/media/badbunny.jpg')
        ->setPrice(130.00)
        ->setStatus("Pagado")
        ->setEvent($eventoBadBunny)
        ->setSeat($seient7)
        ->setPurchase($compra1);
    $entityManager->persist($ticket7);

    $ticket8 = (new Ticket())->setCode("BADBUNNY002")
        ->setImg('./src/static/media/badbunny.jpg')
        ->setPrice(85.00)
        ->setStatus("Pagado")
        ->setEvent($eventoBadBunny)
        ->setSeat($seient8)
        ->setPurchase($compra2);
    $entityManager->persist($ticket8);

    $entityManager->flush();
}

$frontController = new FrontController();
$frontController->handleRequest();