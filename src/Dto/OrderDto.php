<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "Order DTO",
    description: "Simple representation of an order"
)]
class OrderDto
{
    #[OA\Property(type: "integer", example: 1)]
    public int $id;

    #[OA\Property(type: "integer", example: 123)]
    public int $customerId;

    #[OA\Property(type: "string", example: "paid")]
    public string $status;

    #[OA\Property(type: "number", format: "float", example: 99.99)]
    public float $totalAmount;

    #[OA\Property(type: "string", example: "2025-05-21 12:34:56")]
    public string $createdAt;

    public function __construct(
        int $id,
        int $customerId,
        string $status,
        float $totalAmount,
        \DateTimeInterface $createdAt
    )
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->status = $status;
        $this->totalAmount = $totalAmount;
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
    }
}