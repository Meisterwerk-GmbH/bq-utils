<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Model\Event\Order;

use Meisterwerk\BqUtils\Model\Event\Order\Location\Location;
use Symfony\Component\Serializer\Attribute\SerializedName;

class Order
{
    /**
     * @param array<int, string> $statuses
     * @param array<int, string> $tagList
     * @param array<int, OrderProperty> $properties
     * @param array<int, TaxValue> $taxValues
     * @param array<int, mixed> $notes
     * @param array<int, OrderLine> $lines
     */
    public function __construct(
        public string $id,
        #[SerializedName('created_at')]
        public \DateTime $createdAt,
        #[SerializedName('updated_at')]
        public \DateTime $updatedAt,
        public ?int $number,
        public string $status,
        public array $statuses,
        #[SerializedName('status_counts')]
        public OrderStatusCounts $statusCounts,
        #[SerializedName('starts_at')]
        public ?\DateTime $startsAt,
        #[SerializedName('stops_at')]
        public ?\DateTime $stopsAt,
        #[SerializedName('deposit_type')]
        public string $depositType,
        #[SerializedName('deposit_value')]
        public mixed $depositValue,
        #[SerializedName('entirely_started')]
        public bool $entirelyStarted,
        #[SerializedName('entirely_stopped')]
        public bool $entirelyStopped,
        #[SerializedName('location_shortage')]
        public ?bool $locationShortage,
        public bool $shortage,
        #[SerializedName('payment_status')]
        public string $paymentStatus,
        #[SerializedName('override_period_restrictions')]
        public bool $overridePeriodRestrictions,
        #[SerializedName('has_signed_contract')]
        public bool $hasSignedContract,
        #[SerializedName('item_count')]
        public int $itemCount,
        #[SerializedName('tag_list')]
        public array $tagList,
        public array $properties,
        #[SerializedName('amount_in_cents')]
        public int $amountInCents,
        #[SerializedName('amount_paid_in_cents')]
        public int $amountPaidInCents,
        #[SerializedName('amount_to_be_paid_in_cents')]
        public ?int $amountToBePaidInCents,
        #[SerializedName('deposit_in_cents')]
        public int $depositInCents,
        #[SerializedName('deposit_held_in_cents')]
        public int $depositHeldInCents,
        #[SerializedName('deposit_paid_in_cents')]
        public int $depositPaidInCents,
        #[SerializedName('deposit_to_be_paid_in_cents')]
        public ?int $depositToBePaidInCents,
        #[SerializedName('deposit_refunded_in_cents')]
        public int $depositRefundedInCents,
        #[SerializedName('deposit_to_refund_in_cents')]
        public int $depositToRefundInCents,
        #[SerializedName('total_in_cents')]
        public int $totalInCents,
        #[SerializedName('total_paid_in_cents')]
        public int $totalPaidInCents,
        #[SerializedName('total_to_be_paid_in_cents')]
        public int $totalToBePaidInCents,
        #[SerializedName('total_discount_in_cents')]
        public int $totalDiscountInCents,
        #[SerializedName('coupon_discount_in_cents')]
        public int $couponDiscountInCents,
        #[SerializedName('discount_in_cents')]
        public int $discountInCents,
        #[SerializedName('grand_total_in_cents')]
        public int $grandTotalInCents,
        #[SerializedName('grand_total_with_tax_in_cents')]
        public int $grandTotalWithTaxInCents,
        #[SerializedName('paid_in_cents')]
        public int $paidInCents,
        #[SerializedName('price_in_cents')]
        public int $priceInCents,
        #[SerializedName('tax_in_cents')]
        public int $taxInCents,
        #[SerializedName('to_be_paid_in_cents')]
        public int $toBePaidInCents,
        #[SerializedName('discount_type')]
        public string $discountType,
        #[SerializedName('discount_percentage')]
        public float $discountPercentage,
        #[SerializedName('billing_address_property_id')]
        public ?string $billingAddressPropertyId,
        #[SerializedName('delivery_address_property_id')]
        public ?string $deliveryAddressPropertyId,
        #[SerializedName('fulfillment_type')]
        public string $fulfillmentType,
        #[SerializedName('delivery_address')]
        public mixed $deliveryAddress,
        #[SerializedName('customer_id')]
        public ?string $customerId,
        #[SerializedName('tax_region_id')]
        public ?string $taxRegionId,
        #[SerializedName('coupon_id')]
        public ?string $couponId,
        #[SerializedName('start_location_id')]
        public string $startLocationId,
        #[SerializedName('stop_location_id')]
        public string $stopLocationId,
        #[SerializedName('order_delivery_rate_id')]
        public ?string $orderDeliveryRateId,
        public ?Customer $customer,
        public mixed $coupon,
        #[SerializedName('start_location')]
        public ?Location $startLocation,
        #[SerializedName('stop_location')]
        public ?Location $stopLocation,
        #[SerializedName('tax_values')]
        public ?array $taxValues,
        public ?Barcode $barcode,
        public ?array $notes,
        public ?array $lines,
    ) {
    }

    public function getPropertyValue(string $identifier): mixed
    {
        foreach ($this->properties as $property) {
            if ($property->identifier === $identifier) {
                return $property->value;
            }
        }

        return null;
    }
}
