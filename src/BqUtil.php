<?php

namespace Meisterwerk\BqUtils;

class BqUtil
{
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
}
