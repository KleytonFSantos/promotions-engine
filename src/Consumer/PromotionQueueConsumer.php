<?php

namespace App\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

class PromotionQueueConsumer
{
    public function __construct()
    {
    }

    public function execute(AMQPMessage $message): mixed
    {
        return json_decode($message->getBody(), true)['id'];
    }
}
