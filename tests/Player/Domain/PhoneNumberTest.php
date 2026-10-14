<?php

namespace App\Tests\Player\Domain;

use App\Player\Domain\Exception\InvalidPhoneNumberException;
use App\Player\Domain\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PhoneNumberTest extends TestCase
{
    #[DataProvider('validNumbers')]
    public function testNormalizesToInternationalFormat(string $input, string $expected): void
    {
        $this->assertSame($expected, PhoneNumber::fromString($input)->toString());
    }

    public static function validNumbers(): iterable
    {
        yield 'french mobile' => ['0612345678', '+33612345678'];
        yield 'with spaces' => ['06 12 34 56 78', '+33612345678'];
        yield 'with dots' => ['06.12.34.56.78', '+33612345678'];
        yield 'with dashes' => ['06-12-34-56-78', '+33612345678'];
        yield 'landline' => ['01 23 45 67 89', '+33123456789'];
        yield 'already international' => ['+33612345678', '+33612345678'];
        yield 'international with spaces' => ['+33 6 12 34 56 78', '+33612345678'];
    }

    #[DataProvider('invalidNumbers')]
    public function testRejectsInvalidNumbers(string $input): void
    {
        $this->expectException(InvalidPhoneNumberException::class);

        PhoneNumber::fromString($input);
    }

    public static function invalidNumbers(): iterable
    {
        yield 'empty' => [''];
        yield 'letters only' => ['abcdefghij'];
        yield 'too short' => ['0612'];
        yield 'too long' => ['06123456789'];
        yield 'letter inside' => ['06 12 34 56 7a'];
    }

    public function testTwoNumbersWithSameValueAreEqualWhateverTheirFormat(): void
    {
        $this->assertTrue(
            PhoneNumber::fromString('06 12 34 56 78')->equals(PhoneNumber::fromString('+33612345678'))
        );
    }

    public function testTwoDifferentNumbersAreNotEqual(): void
    {
        $this->assertFalse(
            PhoneNumber::fromString('0612345678')->equals(PhoneNumber::fromString('0687654321'))
        );
    }
}
