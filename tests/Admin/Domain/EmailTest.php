<?php

namespace App\Tests\Admin\Domain;

use App\Admin\Domain\Email;
use App\Admin\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCreatesEmailFromValidString(): void
    {
        $email = Email::fromString('hassan@ca-venette.fr');

        $this->assertSame('hassan@ca-venette.fr', $email->toString());
    }

    public function testThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('pas-un-email');
    }

    public function testTwoEmailsWithSameValueAreEqual(): void
    {
        $email1 = Email::fromString('hassan@ca-venette.fr');
        $email2 = Email::fromString('hassan@ca-venette.fr');

        $this->assertTrue($email1->equals($email2));
    }

    public function testTwoEmailsWithDifferentValuesAreNotEqual(): void
    {
        $email1 = Email::fromString('hassan@ca-venette.fr');
        $email2 = Email::fromString('autre@ca-venette.fr');

        $this->assertFalse($email1->equals($email2));
    }
}
