<?php

namespace Meisterwerk\BqUtils\OrderProperties;

enum PropertyTypesV1: string
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
            PropertyTypesV1::TEXT_FIELD, PropertyTypesV1::TEXT_AREA => true,
            PropertyTypesV1::PHONE, PropertyTypesV1::SELECT, PropertyTypesV1::ADDRESS => false,
        };
    }
}
