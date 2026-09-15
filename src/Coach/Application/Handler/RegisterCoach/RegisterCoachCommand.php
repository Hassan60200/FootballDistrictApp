<?php

namespace App\Coach\Application\Handler\RegisterCoach;

final class RegisterCoachCommand
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ) {}
}
