<?php

namespace Meisterwerk\BqUtils\MW;

/**
 * The address is sometimes extracted directly from the DeliveryOption,
 * so we keep the exact string instead of using a regex.
 * Pickup locations will probably be discontinued in the future.
 */
enum DeliveryOption: string
{
    case PICKUP_BERN = 'Abholung Bern (Sulgenauweg 31, im Hinterhof, 3007 Bern)';

    case PICKUP_BASEL = 'Abholung Basel (Engelgasse 123, 4052 Basel)';

    case PICKUP_ZURICH = 'Abholung Zürich (Sägereistrasse 21, 8152 Glattbrugg)';

    case PICKUP_ST_GALLEN = 'Abholung St. Gallen (Fürstenlandstrasse 35, 9000 St. Gallen)';

    case SHIPPING = 'Versand';

    case COURIER_ZURICH = 'Kurier Zürich';

    case COURIER_BERN = 'Kurier Bern';

    public function isPickup(): bool
    {
        return match($this)
        {
            DeliveryOption::PICKUP_BERN,
            DeliveryOption::PICKUP_BASEL,
            DeliveryOption::PICKUP_ZURICH,
            DeliveryOption::PICKUP_ST_GALLEN
            => true,

            DeliveryOption::SHIPPING,
            DeliveryOption::COURIER_ZURICH,
            DeliveryOption::COURIER_BERN,
            => false,
        };
    }

    public function isShipping(): bool
    {
        return match($this)
        {
            DeliveryOption::SHIPPING => true,

            DeliveryOption::COURIER_ZURICH,
            DeliveryOption::COURIER_BERN,
            DeliveryOption::PICKUP_BERN,
            DeliveryOption::PICKUP_BASEL,
            DeliveryOption::PICKUP_ZURICH,
            DeliveryOption::PICKUP_ST_GALLEN
            => false,
        };
    }

}
