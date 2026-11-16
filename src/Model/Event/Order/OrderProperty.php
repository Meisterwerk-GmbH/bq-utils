<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Symfony\Component\Serializer\Attribute\SerializedName;

class OrderProperty
{
    /**
     * @param array<int, string> $showOn
     */
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public string $name,
        public string $identifier,
        public int $position,
        #[SerializedName('property_type')]
        public string $propertyType,
        #[SerializedName('show_on')]
        public array $showOn,
        #[SerializedName('validation_required')]
        public bool $validationRequired,
        #[SerializedName('meets_validation_requirements')]
        public bool $meetsValidationRequirements,
        public mixed $value,
        #[SerializedName('default_property_id')]
        public string $defaultPropertyId,
        #[SerializedName('owner_id')]
        public string $ownerId,
        #[SerializedName('owner_type')]
        public string $ownerType,
    ) {
    }
}
