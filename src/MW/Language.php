<?php

namespace Meisterwerk\BqUtils\MW;

enum Language: string
{
    case GERMAN = 'Deutsch';

    case FRENCH = 'Francais';

    case ENGLISH = 'English';

    public function getIsoLanguageCode(): string
    {
        return match ($this) {
            self::GERMAN => 'de',
            self::FRENCH => 'fr',
            self::ENGLISH => 'en',
        };
    }

    public static function fromOrderDataV4($orderData): Language
    {
        $orderProperties = $orderData->attributes->properties;
        if (property_exists($orderProperties, 'sprache')) {
            $languageString = $orderProperties->sprache;
            $language = self::tryFrom($languageString);
        }
        return $language ?? self::GERMAN;
    }
}
