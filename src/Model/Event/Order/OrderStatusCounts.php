<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

class OrderStatusCounts
{
    public function __construct(
        public int $draft,
        public int $reserved,
        public int $started,
        public int $stopped,
    ) {
    }
}
