<?php

namespace App\Match\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class MatchDateTime
{
    private function __construct(
        #[ORM\Column(type: 'datetime_immutable')]
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
