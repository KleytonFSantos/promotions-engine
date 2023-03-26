<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
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
        readonly private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        int $id,
        Request $request,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionFilter
    ): Response {
        if ($request->headers->has('force-fail')) {
            return new JsonResponse([
                'error' => 'The requested engine was failure message.'
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

        return new Response($responseContent, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param DTOSerializer $serializer
     * @param int $id
     * @return Response
     */
    #[Route('products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions(DTOSerializer $serializer,int $id): Response
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        $promotionForProduct = $this->entityManager->getRepository(Promotion::class)
            ->findPromotionByProduct($product);

        $responseContent = $serializer->serialize($promotionForProduct, 'json');

        return new Response(
            $responseContent,
        200,
            ['Content-Type' => 'application/json']
        );
    }
}
