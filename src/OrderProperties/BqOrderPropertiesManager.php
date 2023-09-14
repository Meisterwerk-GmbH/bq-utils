<?php

namespace Meisterwerk\BqUtils\OrderProperties;

use Meisterwerk\BqUtils\BqRequestException;
use Meisterwerk\BqUtils\BqRestManager;
use RuntimeException;

class BqOrderPropertiesManager
{
    private BqRestManager $bqRestManagerV1;

    private BqRestManager $bqRestManagerV3;

    public function __construct(BqRestManager $bqRestManagerV1, BqRestManager $bqRestManagerV3)
    {
        $this->bqRestManagerV1 = $bqRestManagerV1;
        $this->bqRestManagerV3 = $bqRestManagerV3;
    }

    /**
     * @throws BqRequestException
     */
    public function createOrUpdateProperty(string $orderId, string $value, BqOrderPropertyQuery $propertyQuery): void
    {
        $properties = self::getProperties($orderId)->data;
        $filteredProperties = array_filter($properties, fn($property) => $property->attributes->name === $propertyQuery->getName());
        $propertyToSet = array_pop($filteredProperties);
        if ($propertyToSet === null) {
            self::createProperty(
                $value,
                $orderId,
                $propertyQuery->getIdentifier(),
                $propertyQuery->getType(),
            );
        } elseif (count($filteredProperties) > 1) {
            throw new RuntimeException('more than one matching property found with the name: ' . $propertyQuery->getName());
        } else {
            self::updateProperty(
                $propertyToSet->attributes->value . "\n" . $value,
                $orderId,
                $propertyQuery->getIdentifier(),
                $propertyQuery->getType(),
                $propertyToSet->id
            );
        }
    }

    /**
     * @throws BqRequestException
     */
    private function getProperties(string $orderId)
    {
        return $this->bqRestManagerV3->get('properties?filter[owner_id]=' . $orderId);
    }

    /**
     * @throws BqRequestException
     */
    private function createProperty(string $value, string $orderId, string $propertyIdentifier, string $propertyType): void
    {
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $value,
                'owner_id' => $orderId,
                'isNew' => 'true',
                'property_type' => $propertyType,
                'type' => $propertyType,
                'owner_type' => 'Order',
            ],
        ];
        $this->bqRestManagerV1->post('properties', $postFields);
    }

    /**
     * @throws BqRequestException
     */
    private function updateProperty(string $value, string $orderId, string $propertyIdentifier, string $propertyType, string $propertyId): void
    {
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $value,
                'owner_id' => $orderId,
                'property_type' => $propertyType,
                'type' => $propertyType,
                'owner_type' => "Order"
            ]
        ];
        $this->bqRestManagerV1->put('properties/' . $propertyId, $postFields);
    }
}