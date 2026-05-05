<?php
declare(strict_types=1);


namespace Meisterwerk\BqUtils\MW;

enum Environment: string
{
    case Development = 'Entwicklung';
    case Production = 'Produktion';
}
