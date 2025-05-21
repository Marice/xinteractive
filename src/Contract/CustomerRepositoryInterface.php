<?php

namespace App\Contract;

use App\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function find(int $id): ?Customer;
    public function findByEmail(string $email): ?Customer;
    public function save(Customer $customer): void;
}