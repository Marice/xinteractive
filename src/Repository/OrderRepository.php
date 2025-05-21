<?php

namespace App\Repository;

use App\Contract\OrderRepositoryInterface;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function find(int $id): ?Order
    {
        return $this->em->getRepository(Order::class)->find($id);
    }

    public function findAllActive(): array
    {
        return $this->em->getRepository(Order::class)->findBy([
            'archived' => false
        ]);
    }

    public function findActiveByCustomer(int $customerId): array
    {
        return $this->em->getRepository(Order::class)->findBy([
            'customer' => $customerId,
            'archived' => false
        ]);
    }

    public function save(Order $order): void
    {
        $this->em->persist($order);
        $this->em->flush();
    }
}