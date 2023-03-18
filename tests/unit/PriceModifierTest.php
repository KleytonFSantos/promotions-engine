<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Tests\ServiceTestCase;

class PriceModifierTest extends ServiceTestCase
{
    /** @test */
    public function DateRangeMultiplier_returns_a_correctly_modified_price(): void
    {
        // Given
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');

        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(["to" => "2022-11-28","from" => "2022-11-25"]);
        $promotion->setType('date_range_multiplier');

        $dateRangeModifier = new DateRangeMultiplier();
        // When
        $modifierPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);

        // Then
        $this->assertEquals(250, $modifierPrice);
    }

    public function FixedPriceVoucher_returns_a_correctly_modified_price(): void
    {
    }


}