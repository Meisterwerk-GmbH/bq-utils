<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event;

use Meisterwerk\BqUtils\Model\Event\Order\Order;
use Symfony\Component\Serializer\Attribute\SerializedName;

class Event
{
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        public string $event,
        #[SerializedName('resource_type')]
        public string $resourceType,
        #[SerializedName('data')]
        public Order $order,
        #[SerializedName('updated_at')]
        public ?\DateTime $updatedAt = null,
        public int $version = 4,
    ) {
    }
}
