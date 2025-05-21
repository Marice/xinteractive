<?php

namespace App\Service;

use App\Contract\CustomerRepositoryInterface;
use App\Contract\OrderServiceInterface;
use App\Contract\ProductRepositoryInterface;
use App\Dto\CreateOrderRequestDTO;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function createOrder(CreateOrderRequestDTO $dto): Order
    {
        $customer = $this->customerRepository->find($dto->customerId);

        if (!$customer) {
            throw new \InvalidArgumentException('Customer not found');
        }

        if (count($dto->items) === 0) {
            throw new \InvalidArgumentException('Order must contain at least one item');
        }

        $order = new Order();
        $order->setCustomer($customer);
        $order->setStatus('created');
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setArchived(false);

        $totalAmount = 0;

        foreach ($dto->items as $itemData) {
            if (!isset($itemData['productId'], $itemData['quantity'])) {
                throw new \InvalidArgumentException('Each item must have productId and quantity');
            }

            $product = $this->productRepository->find($itemData['productId']);
            if (!$product) {
                throw new \InvalidArgumentException("Product with ID {$itemData['productId']} not found");
            }

            if ($itemData['quantity'] <= 0) {
                throw new \InvalidArgumentException('Quantity must be positive');
            }

            if ($product->getStock() < $itemData['quantity']) {
                throw new \InvalidArgumentException("Not enough stock for product '{$product->getName()}'");
            }

            $product->setStock($product->getStock() - $itemData['quantity']);

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($itemData['quantity']);
            $orderItem->setUnitPrice($product->getPrice());
            $orderItem->setOrder($order);

            $order->addItem($orderItem);

            $totalAmount += ($product->getPrice() * $itemData['quantity']);
        }

        $order->setTotalAmount($totalAmount);

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}