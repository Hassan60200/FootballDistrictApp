<?php

namespace App\Tests\Coach\Domain;

use App\Coach\Domain\Email;
use App\Coach\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCreatesEmailFromValidString(): void
    {
        $email = Email::fromString('zidane@ca-venette.fr');

        $this->assertSame('zidane@ca-venette.fr', $email->toString());
    }

    public function testThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('pas-un-email');
    }
}
