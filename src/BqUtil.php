<?php

namespace Meisterwerk\BqUtils;

use Meisterwerk\BqUtils\MW\MwBqUtil;

class BqUtil
{
    /**
     * @deprecated see BqUtil->extractBqPropertyValue, this function was renamed
     */
    public static function extractBqProperty($properties, $identifier): string {
        return self::extractBqPropertyValueV1($properties, $identifier);
    }

    /**
     * @deprecated see BqUtil->extractBqPropertyValueV1, this function was renamed
     */
    public static function extractBqPropertyValue($properties, $identifier): string {
        return self::extractBqPropertyValueV1($properties, $identifier);
    }

    /**
     * @deprecated see BqUtil->extractBqPropertyObjectV1, this function was renamed
     */
    public static function extractBqPropertyObject($properties, $identifier) {
        return self::extractBqPropertyObjectV1($properties, $identifier);
    }

    /**
     * @deprecated see BqUtil->extractBqPropertyValueV4, API version V1 will no longer be supported
     */
    public static function extractBqPropertyValueV1($properties, $identifier): string {
        // array_filter keeps keys -> we have to reindex it with array_values
        $matchingProperties = array_values(
            array_filter(
                $properties,
                fn($p) => $p->identifier === $identifier
            )
        );
        return count($matchingProperties) === 1 ? $matchingProperties[0]->value : '';
    }

    /**
     * @deprecated see BqUtil->extractBqPropertyObjectV4, API version V1 will no longer be supported
     */
    public static function extractBqPropertyObjectV1($properties, $identifier) {
        $matchingProperties = array_filter(
            $properties,
            fn ($property) => ($property->identifier === $identifier)
        );
        if (count($matchingProperties) === 1) {
            return array_pop($matchingProperties);
        }
        return null;
    }

    public static function extractBqPropertyValueV4($properties, $identifier): string {
        // array_filter keeps keys -> we have to reindex it with array_values
        $matchingProperties = array_values(
            array_filter(
                $properties,
                fn($p) => $p->attributes->identifier === $identifier
            )
        );
        return count($matchingProperties) === 1 ? $matchingProperties[0]->attributes->value : '';
    }

    public static function extractBqPropertyObjectV4($properties, $identifier) {
        $matchingProperties = array_filter(
            $properties,
            fn ($property) => ($property->attributes->identifier === $identifier)
        );
        if (count($matchingProperties) === 1) {
            return array_pop($matchingProperties);
        }
        return null;
    }

    /**
     * @deprecated see BqUtil->attachChildrenToParentsV1, this function was renamed
     *
     * generates the following structure from bq-lines:
     *
     * [
     *      [
     *          'bqLine' => NORMAL-BQ-LINE
     *          'childBQLines' => ARRAY-OF-BQ-LINES
     *      ],
     *      ...
     * ]
     *
     */
    public static function attachChildrenToParents($bqLines): array {
        return self::attachChildrenToParentsV1($bqLines);
    }

    /**
     * @deprecated see BqUtil->attachChildrenToParentsV4, API version V1 will no longer be supported
     *
     * generates the following structure from bq-lines:
     *
     * [
     *      [
     *          'bqLine' => NORMAL-BQ-LINE
     *          'childBQLines' => ARRAY-OF-BQ-LINES
     *      ],
     *      ...
     * ]
     */
    public static function attachChildrenToParentsV1($bqLines): array {
        // get all lines without parent
        $lines = array_map(
            fn($l) => ['bqLine' => $l, 'childBQLines' => []],
            array_filter(
                $bqLines,
                fn($l) => is_null($l->parent_line_id)
            )
        );

        // get all child-lines
        $childBQLines = array_filter(
            $bqLines,
            fn($l) => !is_null($l->parent_line_id)
        );

        // append all child-lines to their parents
        foreach ($childBQLines as $childBQLine) {
            foreach ($lines as $lineIndex => $line) {
                if ($line['bqLine']->id === $childBQLine->parent_line_id) {
                    $lines[$lineIndex]['childBQLines'][] = $childBQLine;
                }
            }
        }

        return $lines;
    }

    /**
     * generates the following structure from bq-lines:
     *
     * [
     *      [
     *          'bqLine' => NORMAL-BQ-LINE
     *          'childBQLines' => ARRAY-OF-BQ-LINES
     *      ],
     *      ...
     * ]
     */
    public static function attachChildrenToParentsV4($bqLines): array {
        // get all lines without parent
        $lines = array_map(
            fn($l) => ['bqLine' => $l, 'childBQLines' => []],
            array_filter(
                $bqLines,
                fn($l) => is_null($l->attributes->parent_line_id)
            )
        );

        // get all child-lines
        $childBQLines = array_filter(
            $bqLines,
            fn($l) => !is_null($l->attributes->parent_line_id)
        );

        // append all child-lines to their parents
        foreach ($childBQLines as $childBQLine) {
            foreach ($lines as $lineIndex => $line) {
                if ($line['bqLine']->id === $childBQLine->attributes->parent_line_id) {
                    $lines[$lineIndex]['childBQLines'][] = $childBQLine;
                }
            }
        }

        return $lines;
    }


    /**
     * @throws BqRequestException
     */
    public static function request($curlOptions, $jsonAssociative = false, $jsonDecode = true) {
        $curl = curl_init();

        // CURLOPT_FAILONERROR is disabled intentionally, otherwise it is not possible to access the Error-Response
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_ENCODING, '');

        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($response === false) {
            throw new BqRequestException(curl_error($curl));
        }
        if($httpCode >= 400) {
            throw new BqRequestException($response, $httpCode);
        }

        curl_close($curl);
        if($jsonDecode) {
            return json_decode($response, $jsonAssociative);
        }
        return $response;
    }

    /**
     * @throws BqRequestException
     * @deprecated see BqUtil->request, this function was renamed
     */
    public static function requestFunction($curlOptions, $jsonAssociative = false) {
        return self::request($curlOptions, $jsonAssociative);
    }

    /**
     * @deprecated see MwBqUtil->getOrderLinkHtmlV1, this function was moved and renamed
     */
    public static function getOrderLinkHtml($order): string {
        return MwBqUtil::getOrderLinkHtmlV1($order);
    }
}
