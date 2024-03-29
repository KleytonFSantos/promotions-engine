<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Services\CreatePromotionsService;
use App\Services\NewQueueService;
use App\Services\PromotionQueueService;
use App\Services\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public function __construct(
        readonly private ProductRepository $productRepository,
        readonly private PromotionQueueService $promotionQueueService,
        readonly private NewQueueService $newQueueService,
        readonly private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        int $id,
        Request $request,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionFilter
    ): JsonResponse {
        if ($request->headers->has('force-fail')) {
            return new JsonResponse([
                'error' => 'The requested engine was failure message.',
            ], $request->headers->get('force-fail'));
        }

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        $product = $this->productRepository->find($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
            $product,
            date_create_immutable($lowestPriceEnquiry->getRequestDate())
        );

        $modifiedEnquiry = $promotionFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new JsonResponse(
            $responseContent,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions(DTOSerializer $serializer, Product $product): JsonResponse
    {
        $this->newQueueService->send($product->getPrice());
        $this->promotionQueueService->send($product->getId());

        $promotionForProduct = $this->entityManager->getRepository(Promotion::class)
            ->findPromotionByProduct($product);

        $responseContent = $serializer->serialize($promotionForProduct, 'json');

        return new JsonResponse(
            $responseContent,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('products/{id}/create-promotions', name: 'create-promotions', methods: 'POST')]
    public function createPromotion(
        int $id,
        Request $request,
        DTOSerializer $serializer,
        CreatePromotionsService $createPromotionsService
    ): JsonResponse {
        $promotion = $serializer->deserialize(
            $request->getContent(),
            Promotion::class,
            'json'
        );
        try {
            $createPromotionsService->handle($promotion, $id);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create promotion'], 500);
        }

        $responseContent = $serializer->serialize($promotion, 'json');

        return new JsonResponse(
            $responseContent,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
