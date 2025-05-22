<?php

namespace App\Contract;

use App\Entity\Order;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Order;

    /**
     * @return Order[]
     */
    public function findActiveByCustomer(
        int $customerId
    ): array;

    /**
     * @return Order[]
     */
    public function findAllActive(): array;
}