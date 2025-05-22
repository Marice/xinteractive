<?php

namespace App\Contract;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function find(
        int $id
    ): ?Product;
}