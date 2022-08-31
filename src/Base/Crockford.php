<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math\Base;

use Ocubom\Math\AbstractBase;
use Ocubom\Math\Base;
use Ocubom\Math\Exception\InvalidArgumentException;

/**
 * A base for Douglas Crockford Base 32 encoding.
 *
 * @see https://www.crockford.com/base32.html
 */
class Crockford extends AbstractBase
{
    /**
     * Generic map of symbols.
     */
    const MAP = [
        '' => '0123456789ABCDEFGHJKMNPQRSTVWXYZ',
        0 => 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A' => 10, 'B' => 11,
        'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17,
        'I' => 01, 'J' => 18, 'K' => 19, 'L' => 01, 'M' => 20, 'N' => 21,
        'O' => 00, 'P' => 22, 'Q' => 23, 'R' => 24, 'S' => 25, 'T' => 26,
        'V' => 27, 'W' => 28, 'X' => 29, 'Y' => 30, 'Z' => 31, 'a' => 10,
        'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16,
        'h' => 17, 'i' => 01, 'j' => 18, 'k' => 19, 'l' => 01, 'm' => 20,
        'n' => 21, 'o' => 00, 'p' => 22, 'q' => 23, 'r' => 24, 's' => 25,
        't' => 26, 'v' => 27, 'w' => 28, 'x' => 29, 'y' => 30, 'z' => 31,
    ];

    /**
     * Map for symbols used only on checksums.
     */
    const CHECKSUM = [
        '' => '*~$=U',
        '*' => 32,
        '~' => 33,
        '$' => 34,
        '=' => 35,
        'U' => 36,
        'u' => 36,
    ];

    /** @var bool */
    private $checksum;

    /**
     * @param bool $checksum The number includes an extra digit for checksum
     */
    public function __construct(bool $checksum = false)
    {
        $this->checksum = $checksum;
    }

    public function getMap(): array
    {
        return self::MAP;
    }

    public function filterValue($value): string
    {
        $number = (is_numeric($value) ? strval($value) : $value) ?: ($this->checksum ? '00' : '0');

        // Ignore hyphens (improving readability)
        $number = str_replace('-', '', $number);

        // Extract checksum
        if ($this->checksum) {
            $check = substr($number, -1);
            if (!isset(self::MAP[$check]) && !isset(self::CHECKSUM[$check])) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid %s check symbol "%s" found on "%s"',
                    $this,
                    $check,
                    $value
                ));
            }

            $number = substr($number, 0, -1);
        }

        // Perform standard checks
        $number = parent::filterValue($number);

        // Verify checksum value
        if ($this->checksum && ($check !== $valid = $this->checksum($number))) {
            throw new InvalidArgumentException(sprintf(
                'Invalid %s checksum for "%s", found "%s" must be "%s"',
                $this,
                $value,
                $check,
                $valid
            ));
        }

        return $number;
    }

    public function returnValue($value): string
    {
        $value = parent::returnValue($value);

        return $value.($this->checksum ? self::checksum($value) : '');
    }

    /**
     * Calculate crockford checksum for number.
     *
     * @param string $number The crockford number
     *
     * @return string The crockford encoded checksum
     */
    private static function checksum(string $number): string
    {
        $value = str_replace('-', '', $number);
        $value = Base::convert($value, new Crockford(false), 10);
        $check = self::mod($value, 37);

        return substr(self::MAP[''].self::CHECKSUM[''], $check, 1);
    }

    /**
     * Remainder (modulo) of the division of the arguments.
     *
     * @param string $num1 The dividend (base-10 string)
     * @param int    $num2 The divisor
     *
     * @return string Remainder of $num1/$num2
     */
    private static function mod(string $num1, int $num2): string
    {
        $mod = 0;
        foreach (str_split($num1) as $digit) {
            $mod = (10 * $mod + (int) $digit) % $num2;
        }

        return $mod;
    }
}
