<?php

namespace Meisterwerk\BqUtils\OrderProperties;

use Meisterwerk\BqUtils\BqRequestException;
use Meisterwerk\BqUtils\BqRestManager;
use RuntimeException;

class BqOrderPropertiesManager
{
    private BqRestManager $bqRestManagerV1;

    private BqRestManager $bqRestManagerV3;

    private BqRestManager $bqRestManagerV4;


    public function __construct(BqRestManager $bqRestManagerV1, BqRestManager $bqRestManagerV3, BqRestManager $bqRestManagerV4)
    {
        $this->bqRestManagerV1 = $bqRestManagerV1;
        $this->bqRestManagerV3 = $bqRestManagerV3;
        $this->bqRestManagerV4 = $bqRestManagerV4;
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqOrderPropertiesManager->createOrUpdateStringProperty, this function was renamed
     */
    public function createOrUpdateProperty(string $orderId, string $value, BqOrderPropertyQuery $propertyQuery): void
    {
        self::createOrUpdateStringPropertyV1($orderId, $value, $propertyQuery);
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqOrderPropertiesManager->createOrUpdateStringPropertyV1, this function was renamed
     */
    public function createOrUpdateStringProperty(
        string $orderId, string $value, BqOrderPropertyQuery $propertyQuery
    ): void
    {
        $this->createOrUpdateStringPropertyV1($orderId, $value, $propertyQuery);
    }


    /**
     * @throws BqRequestException
     */
    public function createOrUpdateStringPropertyV1(
        string $orderId, string $value, BqOrderPropertyQuery $propertyQuery
    ): void
    {
        $properties = self::getPropertiesV1($orderId)->data;
        $matchingProperties = array_filter(
            $properties,
            fn($property) => $property->attributes->name === $propertyQuery->getName()
        );
        $propertyToSet = array_pop($matchingProperties);
        if ($propertyToSet === null) {
            self::createPropertyV1(
                $value,
                $propertyQuery->getIdentifier(),
                $orderId,
            );
        } elseif (count($matchingProperties) === 0) {
            self::updatePropertyV1(
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
    public function createOrUpdateStringPropertyV4(
        string $orderId, string $value, BqOrderPropertyQuery $propertyQuery
    ): void
    {
        $properties = self::getPropertiesV4($orderId)->data;
        $matchingProperties = array_filter(
            $properties,
            fn($property) => $property->attributes->name === $propertyQuery->getName()
        );
        $propertyToSet = array_pop($matchingProperties);
        if ($propertyToSet === null) {
            self::createPropertyV4(
                $value,
                $propertyQuery,
                $orderId,
            );
        } elseif (count($matchingProperties) === 0) {
            self::updatePropertyV4(
                $value,
                $propertyToSet,
            );
        } else {
            throw new RuntimeException('more than one matching property found with the name: ' . $propertyQuery->getName());
        }
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqOrderPropertiesManager->getPropertiesV1, this function was renamed
     */
    private function getProperties(string $orderId)
    {
        return $this->getPropertiesV1($orderId);
    }

    /**
     * @throws BqRequestException
     */
    private function getPropertiesV1(string $orderId)
    {
        return $this->bqRestManagerV3->get('properties?filter[owner_id]=' . $orderId);
    }

    /**
     * @throws BqRequestException
     */
    private function getPropertiesV4(string $orderId)
    {
        return $this->bqRestManagerV4->get('properties?filter[owner_id][eq]=' . $orderId);
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqOrderPropertiesManager->createPropertyV1, this function was renamed
     */
    private function createProperty(string $value, string $propertyIdentifier, string $orderId): void
    {
        $this->createPropertyV1($value, $propertyIdentifier, $orderId);
    }

    /**
     * @throws BqRequestException
     */
    private function createPropertyV1(string $value, string $propertyIdentifier, string $orderId): void
    {
        $sessionProperties = $this->bqRestManagerV1->get('session')->default_properties;
        $matchingProperties = array_filter(
            $sessionProperties,
            fn($p) => $p->identifier === $propertyIdentifier
        );
        if (count($matchingProperties) === 1) {
            $propertyType = PropertyTypesV1::from(array_pop($matchingProperties)->property_type);
        } else {
            throw new BqRequestException("no matching property found in session");
        }
        if (!$propertyType->isStringProperty()) {
            throw new RuntimeException("currently, only single- and multi-line-text-properties are tested");
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
    private function createPropertyV4(string $value, BqOrderPropertyQuery $property, string $orderId): void
    {
        $defaultProperties = $this->bqRestManagerV4->get('default_properties')->data;
        $matchingProperties = array_filter(
            $defaultProperties,
            fn($p) => $p->attributes->identifier === $property->getIdentifier()
        );
        if (count($matchingProperties) === 1) {
            $propertyType = PropertyTypesV4::from(array_pop($matchingProperties)->attributes->property_type);
        } else {
            throw new BqRequestException("no matching property found in session");
        }
        if (!$propertyType->isStringProperty()) {
            throw new RuntimeException("currently, only single- and multi-line-text-properties are tested");
        }
        $postFields = [
            'data' => [
                'type' => 'properties',
                'attributes' => [
                    'property_type' => $propertyType->value,
                    'name' => $property->getName(),
                    'identifier' => $property->getIdentifier(),
                    'value' => $value,
                    'owner_id' => $orderId,
                    'owner_type' => 'orders'
                ]
            ],
        ];
        $this->bqRestManagerV4->post('properties', $postFields);
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqOrderPropertiesManager->updatePropertyV1, this function was renamed
     */
    private function updateProperty(string $value, string $propertyIdentifier, $propertyToSet): void
    {
        $this->updatePropertyV1($value, $propertyIdentifier, $propertyToSet);
    }

    /**
     * @throws BqRequestException
     */
    private function updatePropertyV1(string $value, string $propertyIdentifier, $propertyToSet): void
    {
        $propertyType = PropertyTypesV1::from($propertyToSet->attributes->type);
        if (!$propertyType->isStringProperty()) {
            throw new RuntimeException("currently, only single- and multi-line-text-properties are tested");
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

    /**
     * @throws BqRequestException
     */
    private function updatePropertyV4(string $value, $propertyToSet): void
    {
        $propertyType = PropertyTypesV1::from($propertyToSet->attributes->property_type);
        if (!$propertyType->isStringProperty()) {
            throw new RuntimeException("currently, only single- and multi-line-text-properties are tested");
        }
        $newValue = $propertyToSet->attributes->value . "\n" . $value;
        $postFields = [
            'data' => [
                'id' => $propertyToSet->id,
                'type'=> 'properties',
                'attributes' => [
                    'value' => $newValue
                ]
            ]
        ];
        $this->bqRestManagerV4->put('properties/' . $propertyToSet->id, $postFields);
    }
}