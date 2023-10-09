<?php

namespace App\Services;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class NewQueueService
{
    public const PROMOTION_QUEUE_ROUNTING_KEY = 'new_queue';

    public function __construct(private readonly ProducerInterface $producer)
    {
    }

    public function send(int $id): void
    {
        $this->producer->publish(json_encode(['id' => $id]), self::PROMOTION_QUEUE_ROUNTING_KEY);
    }
}