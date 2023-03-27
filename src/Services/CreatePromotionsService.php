<?php

namespace App\Services;

use App\Entity\ProductPromotion;
use App\Entity\Promotion;
use App\Repository\ProductPromotionRepository;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;

class CreatePromotionsService
{
    public function __construct(
        private readonly ProductPromotionRepository $productPromotionRepository,
        private readonly PromotionRepository $promotionRepository,
        private readonly ProductRepository $productRepository
    )
    {

    }
    public function handle(Promotion $promotion, int $id): void
    {
        $this->promotionRepository->save($promotion, true);
        $product = $this->productRepository->find($id);
        $productPromotion = new ProductPromotion();
        $productPromotion->setProduct($product);
        $productPromotion->setPromotion($promotion);
        $productPromotion->setValidTo(null);
        $this->productPromotionRepository->save($productPromotion, true);
    }
}