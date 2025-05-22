<?php

namespace App\Repository;

use App\Contract\ProductRepositoryInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function find(
        int $id
    ): ?Product
    {
        return $this->em->getRepository(Product::class)->find($id);
    }
}