<?php

namespace Meisterwerk\BqUtils;

class BqPropertiesManager
{
    private const BQ_API_PATH_V1 = 'https://rentshop.booqable.com/api/1/';
    private const BQ_API_PATH_V3 = 'https://rentshop.booqable.com/api/3/';

    private BqRestManager $bqRestManagerV1;

    private BqRestManager $bqRestManagerV3;

    public function __construct(string $apiKey)
    {
        $this->bqRestManagerV1 = new BqRestManager($apiKey, self::BQ_API_PATH_V1);
        $this->bqRestManagerV3 = new BqRestManager($apiKey, self::BQ_API_PATH_V3);
    }

    /**
     * @param array $propertyFields example: $propertyFields = ['identifier' => 'sprache', 'name' => 'Sprache',]
     * @throws BqRequestException
     */
    public function createOrUpdateProperty($bqOrderId, $trackingNumber, array $propertyFields): void
    {
        $properties = self::getProperties($bqOrderId)->data;
        $filteredProperties = array_filter($properties, fn($property) => $property->attributes->name === $propertyFields['name']);
        $propertyToSet = array_pop($filteredProperties);
        if ($propertyToSet === null) {
            self::createProperty(
                $trackingNumber,
                $bqOrderId,
                $propertyFields['identifier']
            );
        } else {
            self::updateProperty(
                $propertyToSet->attributes->value . $trackingNumber,
                $bqOrderId,
                $propertyFields['identifier'],
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
    private function createProperty($value, $orderId, $propertyIdentifier): void
    {
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $value,
                'owner_id' => $orderId,
                'isNew' => 'true',
                'property_type' => 'Property::TextField',
                'type' => 'Property::TextField',
                'owner_type' => 'Order',
            ],
        ];
        $this->bqRestManagerV1->post('properties', $postFields);
    }

    /**
     * @throws BqRequestException
     */
    private function updateProperty($value, $orderId, $propertyIdentifier, $propertyId): void
    {
        $postFields = [
            'property' => [
                'identifier' => $propertyIdentifier,
                'value' => $value,
                'owner_id' => $orderId,
                'property_type' => 'Property::TextField',
                'type' => 'Property::TextField',
                'owner_type' => "Order"
            ]
        ];
        $this->bqRestManagerV1->put('properties/' . $propertyId, $postFields);
    }
}