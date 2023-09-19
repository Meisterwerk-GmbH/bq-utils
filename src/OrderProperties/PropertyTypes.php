<?php

namespace Meisterwerk\BqUtils\OrderProperties;

enum PropertyTypes: string
{
    case TEXT_FIELD = 'Property::TextField';

    case TEXT_AREA = 'Property::TextArea';

    case PHONE = 'Property::Phone';

    case SELECT = 'Property::Select';

    case ADDRESS = 'Property::Address';

    public function isStringProperty(): bool
    {
        return match($this)
        {
            PropertyTypes::TEXT_FIELD, PropertyTypes::TEXT_AREA => true,
            PropertyTypes::PHONE, PropertyTypes::SELECT, PropertyTypes::ADDRESS => false,
        };
    }
}
