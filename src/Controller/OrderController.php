<?php

namespace App\Controller;

use App\Contract\OrderRepositoryInterface;
use App\Contract\OrderServiceInterface;
use App\Dto\CreateOrderRequestDTO;
use App\Dto\OrderDto;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[Route('/api/v1/orders')]
class OrderController extends AbstractController
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private EntityManagerInterface $em,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    #[OA\Post(
        path: "/api/v1/orders",
        summary: 'Create orders',
        requestBody: new OA\RequestBody(
            required: true,
        ),
        responses: [
            new OA\Response(response: 201, description: "Order created"),
            new OA\Response(response: 400, description: "Validation error"),
        ]
    )]
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }


        try {
            $requestDto = new CreateOrderRequestDTO($data['customerId'] ?? null, $data['items'] ?? []);
            $order = $this->orderService->createOrder($requestDto);

            $orderDto = new OrderDto(
                $order->getId(),
                $order->getCustomer()->getId(),
                $order->getStatus(),
                $order->getTotalAmount(),
                $order->getCreatedAt()
            );

            return $this->json($orderDto, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => 'Business rule violation',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Unexpected error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[OA\Get(
        path: '/api/v1/orders',
        summary: 'List orders',
        parameters: [
            new OA\Parameter(
                name: 'customerId',
                description: 'Filter orders by customer ID',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns list of orders',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: OrderDto::class))
                )
            )
        ]
    )]
    #[Route('', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $customerId = $request->query->get('customerId');

        if ($customerId) {
            $orders = $this->orderRepository->findActiveByCustomer($customerId);
        } else {
            $orders = $this->orderRepository->findAllActive();
        }

        $orderDtos = array_map(function($order) {
            return new OrderDto(
                id: $order->getId(),
                customerId:   $order->getCustomer()->getId(),
                status: $order->getStatus(),
                totalAmount: $order->getTotalAmount(),
                createdAt: $order->getCreatedAt()
            );
        }, $orders);

        return $this->json($orderDtos);
    }

    #[OA\Get(
        path: '/api/orders/{id}',
        summary: 'Retrieve a single order by ID',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'The ID of the order to retrieve',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order retrieved successfully',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/OrderDto'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Order not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', methods: ['GET'])]
    public function get(
        int $id
    ): JsonResponse
    {
        $order = $this->orderRepository->find($id);


        if (!$order) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $orderDto = new OrderDto(
            id: $order->getId(),
            customerId:   $order->getCustomer()->getId(),
            status: $order->getStatus(),
            totalAmount: $order->getTotalAmount(),
            createdAt: $order->getCreatedAt()
        );

        return $this->json($orderDto);
    }


    #[OA\Delete(
        path: '/api/orders/{id}',
        summary: 'Archive an order (soft delete)',
        tags: ['Orders'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'The ID of the order to archive',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Order successfully archived (soft deleted)'
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Order not found')
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $order->setArchived(true);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
