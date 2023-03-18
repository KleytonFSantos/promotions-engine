<?php

namespace App\Filter\Modifier;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;

interface PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, LowestPriceEnquiry $enquiry): int;
}