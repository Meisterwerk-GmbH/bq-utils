<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Symfony\Component\Serializer\Attribute\SerializedName;

class OrderLine
{
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public bool $archived,
        #[SerializedName('archived_at')]
        public ?\DateTime $archivedAt,
        public string $title,
        #[SerializedName('extra_information')]
        public ?string $extraInformation,
        public float $quantity,
        #[SerializedName('original_price_each_in_cents')]
        public ?int $originalPriceEachInCents,
        #[SerializedName('original_charge_length')]
        public ?int $originalChargeLength,
        #[SerializedName('original_charge_label')]
        public ?string $originalChargeLabel,
        #[SerializedName('price_each_in_cents')]
        public int $priceEachInCents,
        #[SerializedName('price_in_cents')]
        public int $priceInCents,
        public int $position,
        #[SerializedName('nested_position')]
        public int $nestedPosition,
        #[SerializedName('charge_label')]
        public ?string $chargeLabel,
        #[SerializedName('charge_length')]
        public ?int $chargeLength,
        #[SerializedName('price_rule_values')]
        public mixed $priceRuleValues,
        public bool $discountable,
        public bool $taxable,
        #[SerializedName('line_type')]
        public string $lineType,
        public bool $relevant,
        #[SerializedName('order_id')]
        public string $orderId,
        #[SerializedName('item_id')]
        public ?string $itemId,
        #[SerializedName('tax_category_id')]
        public ?string $taxCategoryId,
        #[SerializedName('price_structure_id')]
        public ?string $priceStructureId,
        #[SerializedName('price_tile_id')]
        public ?string $priceTileId,
        #[SerializedName('planning_id')]
        public ?string $planningId,
        #[SerializedName('parent_line_id')]
        public ?string $parentLineId,
        #[SerializedName('bundle_item_id')]
        public ?string $bundleItemId,
        #[SerializedName('owner_id')]
        public string $ownerId,
        #[SerializedName('owner_type')]
        public string $ownerType,
    ) {
    }
}
