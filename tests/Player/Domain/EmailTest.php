<?php

namespace App\Tests\Player\Domain;

use App\Player\Domain\Email;
use App\Player\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCreatesEmailFromValidString(): void
    {
        $email = Email::fromString('karim@ca-venette.fr');

        $this->assertSame('karim@ca-venette.fr', $email->toString());
    }

    public function testThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('pas-un-email');
    }
}
