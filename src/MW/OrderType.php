<?php

namespace Meisterwerk\BqUtils\MW;

enum OrderType: string
{
    case Rental = 'Miete';
    case Purchase = 'Kauf';
    case Viewing = 'Beratung / Besichtigung';
    case Internal = 'Intern';
    case KickBack = 'Kick-Back';
    case Scam = 'Scam';
    case Redundant = 'Mehrfache Anfrage';
}
