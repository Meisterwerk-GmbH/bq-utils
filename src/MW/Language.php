<?php

namespace Meisterwerk\BqUtils\MW;

enum Language: string
{
    case GERMAN = 'Deutsch';

    case FRENCH = 'Francais';

    case ENGLISH = 'English';

    public static function fromOrderAttributes($orderAttributes): Language
    {
        if (property_exists($orderAttributes->properties, 'sprache')) {
            $languageString = $orderAttributes->properties->sprache;
            $language = self::tryFrom($languageString);
        }
        return $language ?? self::GERMAN;
    }

}
