<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Consumer;

use Meisterwerk\BqUtils\Model\Event\Event;
use Symfony\Component\Serializer\SerializerInterface;

class OrderEventConsumer
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function consume(string $payload): Event
    {
        $event = $this->serializer->deserialize($payload, Event::class, 'json');
        if (!$event instanceof Event || $event->resourceType !== 'orders'
            || $event->version !== 4 || !str_starts_with($event->event, 'order.')
            || $event->order->id === '') {
            throw new \UnexpectedValueException('Expected a version 4 Booqable order event.');
        }

        return $event;
    }
}
