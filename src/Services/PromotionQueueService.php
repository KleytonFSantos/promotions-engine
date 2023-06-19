<?php

namespace App\Services;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class PromotionQueueService
{
    public const PROMOTION_QUEUE_ROUNTING_KEY = 'promotions_queue';

    public function __construct(private readonly ProducerInterface $producer)
    {
    }

    public function send(int $id): void
    {
        try {
            $this->producer->publish(json_encode(['id' => $id]), self::PROMOTION_QUEUE_ROUNTING_KEY);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
