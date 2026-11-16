<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Customer
{
    /**
     * @param array<string, mixed> $properties
     * @param array<int, string> $tagList
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
        public int $number,
        public string $name,
        public ?string $email,
        #[SerializedName('deposit_type')]
        public string $depositType,
        #[SerializedName('deposit_value')]
        public int|float|string|null $depositValue,
        #[SerializedName('discount_percentage')]
        public float $discountPercentage,
        #[SerializedName('legal_type')]
        public ?string $legalType,
        #[SerializedName('email_marketing_consented')]
        public ?bool $emailMarketingConsented,
        #[SerializedName('email_marketing_consent_updated_at')]
        public ?\DateTime $emailMarketingConsentUpdatedAt,
        #[SerializedName('order_count')]
        public int $orderCount,
        #[SerializedName('revenue_in_cents')]
        public int $revenueInCents,
        #[SerializedName('balance_due_in_cents')]
        public int $balanceDueInCents,
        #[SerializedName('last_order_at')]
        public ?\DateTime $lastOrderAt,
        #[SerializedName('average_order_value_in_cents')]
        public int $averageOrderValueInCents,
        public array $properties,
        #[SerializedName('tag_list')]
        public array $tagList,
        #[SerializedName('stripe_id')]
        public ?string $stripeId,
        #[SerializedName('merge_suggestion_customer_id')]
        public ?string $mergeSuggestionCustomerId,
        #[SerializedName('tax_region_id')]
        public ?string $taxRegionId,
    ) {
    }
}
