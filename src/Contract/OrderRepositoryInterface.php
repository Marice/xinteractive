<?php

namespace App\Contract;

use App\Entity\Order;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Order;

    /**
     * @return Order[]
     */
    public function findActiveByCustomer(int $customerId): array;

    public function save(Order $order): void;

    /**
     * @return Order[]
     */
    public function findAllActive(): array;
}