<?php

namespace App\Contract;

use App\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function find(
        int $id
    ): ?Customer;
}