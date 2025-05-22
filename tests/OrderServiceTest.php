<?php

namespace App\Tests;

use App\Contract\CustomerRepositoryInterface;
use App\Contract\OrderServiceInterface;
use App\Contract\ProductRepositoryInterface;
use App\Dto\CreateOrderRequestDTO;
use App\Entity\Customer;
use App\Entity\Product;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private MockObject $em;
    private MockObject $customerRepository;
    private MockObject $productRepository;
    private OrderServiceInterface $orderService;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);

        $this->orderService = new OrderService(
            $this->em,
            $this->customerRepository,
            $this->productRepository
        );
    }
    public function testCreateOrderSuccess(): void
    {
        $customer = new Customer();
        $customer->setName('Test');
        $customer->setEmail('test@test.com');

        $product1 = new Product();
        $product1->setId(1);
        $product1->setName('Laptop');
        $product1->setPrice(1000.0);
        $product1->setStock(10);

        $dto = new CreateOrderRequestDTO($customerId = 1, [
            ['productId' => 1, 'quantity' => 2]
        ]);

        $this->customerRepository->method('find')->with($customerId)->willReturn($customer);
        $this->productRepository->method('find')->with(1)->willReturn($product1);
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $order = $this->orderService->createOrder($dto);

        $this->assertEquals($customer, $order->getCustomer());
        $this->assertCount(1, $order->getItems());
        $this->assertEquals(2000.0, $order->getTotalAmount());
    }

    public function testCreateOrderWithInvalidCustomer(): void
    {
        $dto = new CreateOrderRequestDTO(999, [['productId' => 1, 'quantity' => 1]]);

        $this->customerRepository->method('find')->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Customer not found');

        $this->orderService->createOrder($dto);
    }

    public function testCreateOrderWithEmptyItems(): void
    {
        $customer = new Customer();
        $this->customerRepository->method('find')->willReturn($customer);

        $dto = new CreateOrderRequestDTO(1, []);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order must contain at least one item');

        $this->orderService->createOrder($dto);
    }

    public function testCreateOrderWithInsufficientStock(): void
    {
        $customer = new Customer();
        $product = new Product();
        $product->setId(1);
        $product->setName('Telefoon');
        $product->setPrice(500.0);
        $product->setStock(1);

        $dto = new CreateOrderRequestDTO(1, [['productId' => 1, 'quantity' => 2]]);

        $this->customerRepository->method('find')->willReturn($customer);
        $this->productRepository->method('find')->willReturn($product);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Not enough stock for product 'Telefoon'");

        $this->orderService->createOrder($dto);
    }
}
