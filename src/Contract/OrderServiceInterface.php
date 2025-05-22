<?php

namespace App\Contract;

use App\Dto\CreateOrderRequestDTO;
use App\Entity\Order;

interface OrderServiceInterface
{
    public function createOrder(
        CreateOrderRequestDTO $dto
    ): Order;
}
