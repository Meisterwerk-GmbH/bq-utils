<?php

namespace Meisterwerk\BqUtils\OrderProperties;

enum PropertyTypesV4: string
{
    case TEXT_FIELD = 'text_field';

    case TEXT_AREA = 'text_area';

    case PHONE = 'phone';

    case SELECT = 'select';

    case ADDRESS = 'address';

    public function isStringProperty(): bool
    {
        return match($this)
        {
            PropertyTypesV4::TEXT_FIELD, PropertyTypesV4::TEXT_AREA => true,
            PropertyTypesV4::PHONE, PropertyTypesV4::SELECT, PropertyTypesV4::ADDRESS => false,
        };
    }
}
