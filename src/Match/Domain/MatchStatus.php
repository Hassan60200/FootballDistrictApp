<?php

namespace App\Match\Domain;

enum MatchStatus: string
{
    case SCHEDULED = 'scheduled';
    case CANCELLED = 'cancelled';
    case PLAYED = 'played';
}
