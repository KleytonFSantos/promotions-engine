<?php

namespace App\Filter;

use App\DTO\LowestPriceEnquiry;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

interface PromotionsFilterInterface
{
    public function apply(LowestPriceEnquiry $enquiry, Promotion ...$promotion): PromotionEnquiryInterface;
}
