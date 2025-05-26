<?php
namespace Xiringuito\Repository;

use Doctrine\ORM\EntityRepository;
use Xiringuito\Entity\Usuari;
use Xiringuito\Entity\Rol;

class UsuariRepository extends EntityRepository
{
    public function findByEmailAndPassword(string $email, string $plainPassword): ?Usuari
    {
        $user = $this->findOneBy(['email' => $email]);
        if (!$user) {
            return null;
        }
        
        if (password_verify($plainPassword, $user->getContrasenya())) {
            return $user;
        }
        
        return null;
    }
    
    public function createUser(string $name, string $surname, string $email, string $plainPassword): ?Usuari
    {
        $entityManager = $this->getEntityManager();
        
        $rolRepository = $entityManager->getRepository(Rol::class);
        $defaultRol = $rolRepository->find(2);
        
        if (!$defaultRol) {
            throw new \Exception("Rol por defecto no encontrado.");
        }
        
        $user = new Usuari();
        $user->setNom($name);
        $user->setCognom($surname); 
        $user->setEmail($email);
        $user->setContrasenya(password_hash($plainPassword, PASSWORD_DEFAULT));
        $user->setRol($defaultRol);
        $user->setData(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $user;
    }
    
}
