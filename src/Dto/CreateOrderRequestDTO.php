<?php
namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateOrderRequestDTO",
    description: "Request DTO for creating an order"
)]
class CreateOrderRequestDTO
{
    #[OA\Property(type: "integer", description: "Customer ID")]
    public int $customerId;

    #[OA\Property(
        type: "array",
        items: new OA\Items(ref: "#/components/schemas/CreateOrderItemDTO"),
        description: "Order items"
    )]
    public array $items;

    public function __construct(int $customerId, array $items)
    {
        $this->customerId = $customerId;
        $this->items = $items;
    }
}
