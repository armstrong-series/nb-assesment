<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\Interface\UserInterface;
use App\Domain\Model\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $userId): ?User
    {
    
        return $this->entityManager->find(User::class, $userId);
    }

    public function getByEmail(string $email): ?User
    {
       
        return $this->createQueryBuilder('u')
                    ->where('u.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function create(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    private function createQueryBuilder(string $alias): \Doctrine\ORM\QueryBuilder
    {
        return $this->entityManager->getRepository(User::class)->createQueryBuilder($alias);
    }
}

