<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(
        ObjectManager $manager
    ): void
    {
        $customer = new Customer();
        $customer->setId(1);
        $customer->setName('Marice Lamain');
        $customer->setEmail('test@test.nl');
        $manager->persist($customer);

        $product = new Product();
        $product->setId(1);
        $product->setName('Laptop');
        $product->setPrice(999.99);
        $product->setStock(10);
        $manager->persist($product);

        $product = new Product();
        $product->setId(2);
        $product->setName('Mobiele telefoon');
        $product->setPrice(799.99);
        $product->setStock(8);
        $manager->persist($product);

        $manager->flush();
    }
}
