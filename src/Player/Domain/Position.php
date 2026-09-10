<?php

namespace App\Player\Domain;

enum Position: string
{
    case GOALKEEPER = 'goalkeeper';
    case DEFENDER = 'defender';
    case MIDFIELDER = 'midfielder';
    case FORWARD = 'forward';
}
