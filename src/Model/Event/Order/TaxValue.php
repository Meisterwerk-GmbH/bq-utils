<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Symfony\Component\Serializer\Attribute\SerializedName;

class TaxValue
{
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public string $name,
        public float $percentage,
        #[SerializedName('value_in_cents')]
        public int $valueInCents,
        #[SerializedName('tax_rate_id')]
        public string $taxRateId,
        #[SerializedName('owner_id')]
        public string $ownerId,
        #[SerializedName('owner_type')]
        public string $ownerType,
    ) {
    }
}
