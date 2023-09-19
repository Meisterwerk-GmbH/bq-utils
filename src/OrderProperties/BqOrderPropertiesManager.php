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
     * @deprecated see BqOrderPropertiesManager->createOrUpdateStringProperty, this function was renamed
     */
    public function createOrUpdateProperty(string $orderId, string $value, BqOrderPropertyQuery $propertyQuery): void
    {
        self::createOrUpdateStringProperty($orderId, $value, $propertyQuery);
    }

    /**
     * @throws BqRequestException
     */
    public function createOrUpdateStringProperty(string $orderId, string $value, BqOrderPropertyQuery $propertyQuery): void
    {
        $properties = self::getProperties($orderId)->data;
        $matchingProperties = array_filter($properties, fn($property) => $property->attributes->name === $propertyQuery->getName());
        $propertyToSet = array_pop($matchingProperties);
        if ($propertyToSet === null) {
            self::createProperty(
                $value,
                $propertyQuery->getIdentifier(),
                $orderId,
            );
        } elseif (count($matchingProperties) === 0) {
            self::updateProperty(
                $value,
                $propertyQuery->getIdentifier(),
                $propertyToSet,
            );
        } else {
            throw new RuntimeException('more than one matching property found with the name: ' . $propertyQuery->getName());
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
    private function createProperty(string $value, string $propertyIdentifier, string $orderId): void
    {
        $sessionProperties = $this->bqRestManagerV1->get('session')->default_properties;
        $matchingProperties = array_filter($sessionProperties, fn($p) => $p->identifier === $propertyIdentifier);
        if (count($matchingProperties) === 1) {
            $propertyType = PropertyTypes::from(array_pop($matchingProperties)->property_type);
        } else {
            throw new BqRequestException("no matching property found in session");
        }
        if (!$propertyType->isStringProperty()) {
            throw new BqRequestException("property isn't a string property (allowed are: Single- and MultiLineText)");
        }
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $value,
                'owner_id' => $orderId,
                'isNew' => 'true',
                'property_type' => $propertyType->value,
                'type' => $propertyType->value,
                'owner_type' => 'Order',
            ],
        ];
        $this->bqRestManagerV1->post('properties', $postFields);
    }

    /**
     * @throws BqRequestException
     */
    private function updateProperty(string $value, string $propertyIdentifier, $propertyToSet): void
    {   $propertyType = PropertyTypes::from($propertyToSet->attributes->type);
        if (!$propertyType->isStringProperty()) {
            throw new BqRequestException("property isn't a string property (allowed are: Single- and MultiLineText)");
        }
        $newValue = $propertyToSet->attributes->value . "\n" . $value;
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $newValue,
                'owner_id' => $propertyToSet->attributes->owner_id,
                'property_type' => $propertyType->value,
                'type' => $propertyType->value,
                'owner_type' => "Order"
            ]
        ];
        $this->bqRestManagerV1->put('properties/' . $propertyToSet->id, $postFields);
    }
}