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

    public function findInStock(): array
    {
        return $this->em->getRepository(Product::class)->createQueryBuilder('p')
            ->where('p.stock > 0')
            ->getQuery()
            ->getResult();
    }

    public function save(
        Product $product
    ): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }
}