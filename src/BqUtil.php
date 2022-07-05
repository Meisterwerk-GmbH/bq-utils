<?php

namespace Meisterwerk\BqUtils;

class BqUtil
{
    public static function extractBqProperty($properties, $identifier): String {
        // array_filter keeps keys -> we have to reindex it with array_values
        $phoneProperties = array_values(array_filter($properties, fn($p) => $p->identifier === $identifier));
        return count($phoneProperties) === 1 ? $phoneProperties[0]->value : '';
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
     *
     */
    public static function attachChildrenToParents($bqLines):array {

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
}
