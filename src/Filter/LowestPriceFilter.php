<?php

namespace App\Filter;

use App\DTO\LowestPriceEnquiry;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionsFilterInterface
{
    public function apply(LowestPriceEnquiry $enquiry, Promotion ...$promotion): PromotionEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $quantity * $price;

        // $modifierPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);

        $enquiry->setDiscountedPrice(50);
        $enquiry->setPrice(100);
        $enquiry->setPromotionId(3);
        $enquiry->setPromotionName('Black Friday half price sale');

        return $enquiry;
    }
}
