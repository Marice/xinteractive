<?php
namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateOrderRequestDTO",
    description: "Request DTO for creating an order"
)]
class CreateOrderRequestDTO
{
    #[OA\Property(
        description: "Customer ID",
        type: "integer"
    )]
    public int $customerId;

    /**
     * @var array<int, array{productId: int, quantity: int}>
     */
    #[OA\Property(
        description: "Order items",
        type: "array",
        items: new OA\Items(ref: "#/components/schemas/CreateOrderItemDTO")
    )]
    public array $items;

    /**
     * @param array<int, array{productId: int, quantity: int}> $items
     */
    public function __construct(
        int $customerId,
        array $items
    )
    {
        $this->customerId = $customerId;
        $this->items = $items;
    }
}
