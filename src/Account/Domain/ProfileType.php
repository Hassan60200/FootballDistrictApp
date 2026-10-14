<?php

declare(strict_types=1);

namespace App\Account\Domain;

enum ProfileType: string
{
    case PLAYER = 'player';
    case COACH = 'coach';
    case ADMIN = 'admin';
}
