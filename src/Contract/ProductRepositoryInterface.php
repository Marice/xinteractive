<?php

namespace App\Contract;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    public function findInStock(): array;
    public function save(Product $product): void;
}