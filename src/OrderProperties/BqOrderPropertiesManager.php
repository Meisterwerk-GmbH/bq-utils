<?php

namespace Meisterwerk\BqUtils\OrderProperties;

use Meisterwerk\BqUtils\BqRequestException;
use Meisterwerk\BqUtils\BqRestManager;
use RuntimeException;

class BqOrderPropertiesManager
{
    private BqRestManager $bqRestManagerV4;


    public function __construct(BqRestManager $bqRestManagerV4) {
        $this->bqRestManagerV4 = $bqRestManagerV4;
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
     */
    private function getPropertiesV4(string $orderId)
    {
        return $this->bqRestManagerV4->get('properties?filter[owner_id][eq]=' . $orderId);
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
            $propertyType = PropertyTypesV4::from(
                array_pop($matchingProperties)->attributes->property_type
            );
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
     */
    private function updatePropertyV4(string $value, $propertyToSet): void
    {
        $propertyType = PropertyTypesV4::from($propertyToSet->attributes->property_type);
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