<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order\Location;

use Symfony\Component\Serializer\Attribute\SerializedName;

class LocationAddress
{
    public function __construct(
        #[SerializedName('meets_validation_requirements')]
        public bool $meetsValidationRequirements,
        #[SerializedName('first_name')]
        public ?string $firstName,
        #[SerializedName('last_name')]
        public ?string $lastName,
        public ?string $address1,
        public ?string $address2,
        public ?string $city,
        public ?string $region,
        public ?string $zipcode,
        public ?string $country,
        #[SerializedName('country_id')]
        public ?string $countryId,
        #[SerializedName('province_id')]
        public ?string $provinceId,
        public ?float $latitude,
        public ?float $longitude,
        public string $value,
    ) {
    }
}
