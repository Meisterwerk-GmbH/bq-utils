<?php

namespace Meisterwerk\BqUtils;

class BqPropertiesManager
{
    private const BQ_API_PATH_1 = 'https://rentshop.booqable.com/api/1/';
    private const BQ_API_PATH_3 = 'https://rentshop.booqable.com/api/3/';

    /**
     * @param array $propertyFields example: $propertyFields = ['identifier' => 'sprache', 'name' => 'Sprache',]
     * @throws BqRequestException
     */
    public static function createOrUpdateProperty($bqOrderId, $trackingNumber, array $propertyFields, $bqApiKey): void
    {
        $properties = self::getProperties($bqOrderId, $bqApiKey)->data;
        $filteredProperties = array_filter($properties, fn($property) => $property->attributes->name === $propertyFields['name']);
        $propertyToSet = array_pop($filteredProperties);
        if ($propertyToSet === null) {
            self::createProperty(
                $trackingNumber,
                $bqOrderId,
                $propertyFields['identifier'],
                $bqApiKey
            );
        } else {
            self::updateProperty(
                $propertyToSet->attributes->value . $trackingNumber,
                $bqOrderId,
                $propertyFields['identifier'],
                $propertyToSet->id,
                $bqApiKey
            );
        }
    }

    /**
     * @throws BqRequestException
     */
    private static function getProperties(string $orderId, string $bqApiKey)
    {
        return BqUtil::request([
            CURLOPT_URL => self::BQ_API_PATH_3 . 'properties?filter[owner_id]=' . $orderId,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $bqApiKey
            ],
        ]);
    }

    /**
     * @throws BqRequestException
     */
    private static function createProperty($value, $orderId, $propertyIdentifier, $bqApiKey): void
    {
        BqUtil::request([
            CURLOPT_URL => self::BQ_API_PATH_1.'properties',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'property' => [
                    'identifier' => $propertyIdentifier,
                    'value' => $value,
                    'owner_id' => $orderId,
                    'isNew' => 'true',
                    'property_type' => 'Property::TextField',
                    'type' => 'Property::TextField',
                    'owner_type' => 'Order',
                ],
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$bqApiKey,
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);
    }

    /**
     * @throws BqRequestException
     */
    private static function updateProperty($value, $orderId, $propertyIdentifier, $propertyId, $bqApiKey): void
    {
        BqUtil::request([
            CURLOPT_URL => self::BQ_API_PATH_1 . 'properties/' . $propertyId,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode([
                'property' => [
                    'identifier' => $propertyIdentifier,
                    'value' => $value,
                    'owner_id' => $orderId,
                    'property_type' => 'Property::TextField',
                    'type' => 'Property::TextField',
                    'owner_type' => "Order"
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $bqApiKey,
                'Content-Type: application/json'
            ],
        ]);
    }
}