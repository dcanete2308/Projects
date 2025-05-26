<?php
require_once '../bootstrap.php'; 

use Xiringuito\Entity\Usuari;

$repo = $entityManager->getRepository(Usuari::class);

$usuarios = $repo->findAll();

foreach ($usuarios as $usuari) {
    $pass = $usuari->getContrasenya();
    
    if (strlen($pass) < 60) {
        $usuari->setContrasenya(password_hash($pass, PASSWORD_DEFAULT));
        $entityManager->persist($usuari);
    }
}

$entityManager->flush();

echo "Contraseñas actualizadas.\n";
