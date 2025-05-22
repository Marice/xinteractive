<?php

namespace App\Repository;

use App\Contract\CustomerRepositoryInterface;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function find(
        int $id
    ): ?Customer
    {
        return $this->em->getRepository(Customer::class)->find($id);
    }
}