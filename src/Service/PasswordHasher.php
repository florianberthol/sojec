<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDoctrineListener(event: Events::prePersist)]
class PasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function prePersist(PrePersistEventArgs $args): void
    {
        $objet = $args->getObject();
        if ($objet instanceof User) {
            $objet->setPassword(
                $this->passwordHasher->hashPassword($objet, $objet->getPassword())
            );
        }
    }
}