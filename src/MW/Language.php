<?php

namespace Meisterwerk\BqUtils\MW;

enum Language: string
{
    case GERMAN = 'Deutsch';

    case FRENCH = 'Francais';

    case ENGLISH = 'English';

    public static function fromOrderV1($order): Language
    {
        if (property_exists($order->properties_attributes, 'sprache')) {
            $languageString = $order->properties_attributes->sprache;
            $language = self::tryFrom($languageString);
        }
        return $language ?? self::GERMAN;
    }

    public static function fromOrderV4($order): Language
    {
        $orderProperties = $order->data->attributes->properties;
        if (property_exists($orderProperties, 'sprache')) {
            $languageString = $orderProperties->sprache;
            $language = self::tryFrom($languageString);
        }
        return $language ?? self::GERMAN;
    }
}
