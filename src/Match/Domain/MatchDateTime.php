<?php

namespace App\Match\Domain;

final class MatchDateTime
{
    private function __construct(
        private readonly \DateTimeImmutable $value,
    ) {}

    public static function fromDateAndTime(\DateTimeImmutable $date, string $time): self
    {
        [$hours, $minutes] = sscanf($time, '%dH%d');
        return new self($date->setTime($hours, $minutes));
    }

    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->value;
    }
}
