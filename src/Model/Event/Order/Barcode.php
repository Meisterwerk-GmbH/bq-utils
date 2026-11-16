<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Barcode
{
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public string $number,
        #[SerializedName('barcode_type')]
        public string $barcodeType,
        #[SerializedName('image_url')]
        public string $imageUrl,
        #[SerializedName('owner_id')]
        public string $ownerId,
        #[SerializedName('owner_type')]
        public string $ownerType,
    ) {
    }
}
