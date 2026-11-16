<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order\Location;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Location
{
    /**
     * @param array<int, string>|null $clusterIds
     * @param array<int, string> $fulfillmentCapabilities
     */
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public bool $archived,
        #[SerializedName('archived_at')]
        public ?\DateTime $archivedAt,
        public string $name,
        public string $code,
        #[SerializedName('location_type')]
        public string $locationType,
        #[SerializedName('address_line1')]
        public ?string $addressLine1,
        #[SerializedName('address_line2')]
        public ?string $addressLine2,
        public ?string $zipcode,
        public ?string $city,
        public ?string $region,
        public ?string $country,
        #[SerializedName('cluster_ids')]
        public ?array $clusterIds,
        #[SerializedName('pickup_enabled')]
        public bool $pickupEnabled,
        #[SerializedName('delivery_enabled')]
        public bool $deliveryEnabled,
        #[SerializedName('fulfillment_capabilities')]
        public array $fulfillmentCapabilities,
        #[SerializedName('main_address')]
        public LocationAddress $mainAddress,
    ) {
    }
}
