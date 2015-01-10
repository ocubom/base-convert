<?php

/*
 * This file is part of "Base Convert" component.
 *
 * © Oscar Cubo Medina <ocubom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math;

use \RuntimeException;

/**
 * Crockford Base32 encoder/decoder
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
abstract class Crockford
{
    /**
     * Crockford base-32 alphabet.
     *
     * Last 5 symbols are special: used only on CRC. The = symbol is used for
     * padding.
     */
    const CROCKFORD = '0123456789ABCDEFGHJKMNPQRSTVWXYZ*~$=';

    /**
     * Crockford symbols & translations
     */
    const CROCKFORD_SYMBOLS = '0123456789ABCDEFGHJKMNPQRSTVWXYZILO';
    const CROCKFORD_FLIPPED = '0123456789abcdefghijklmnopqrstuv110';

    /**
     * Encode a string using Crockford base-32
     *
     * {@see http://www.crockford.com/wrmg/base32.html}
     *
     * @param mixed   $number   Number to convert
     * @param mixed   $base     Original base for number
     * @param boolean $checksum Add checksum digit
     *
     * @return string
     */
    public static function encode($number, $base = 10, $checksum = false)
    {
        $base10 = Base::convert($number, $base, 10);
        $base32 = Base::convert($base10, 10, 32);

        return sprintf(
            '%s%s',
            // Recode symbols from base-32 value
            strtr(
                $base32,
                substr(self::CROCKFORD_FLIPPED, 0, 32),
                substr(self::CROCKFORD_SYMBOLS, 0, 32)
            ),
            // Calculate CRC from base-10 value
            $checksum ? self::checksum($base10) : ''
        );
    }

    /**
     * Decode a string using Crockford base-32
     *
     * {@see http://www.crockford.com/wrmg/base32.html}
     *
     * @param mixed   $number   The crockford number to convert
     * @param mixed   $base     Desired output base for number
     * @param boolean $checksum Crockford number includes checksum
     *
     * @return string The number decoded to desired base
     */
    public static function decode($number, $base = 10, $checksum = false)
    {
        // Clean number
        $crockford = strtoupper(trim(str_replace('-', '', $number)));

        // Recode symbols (base-32) and convert to base-10
        $base32 = strtr(
            $checksum ? substr($crockford, 0, -1) : $crockford,
            self::CROCKFORD_SYMBOLS,
            self::CROCKFORD_FLIPPED
        );
        $base10 = Base::convert($base32, 32, 10);

        // Verify checksum
        if ($checksum && substr($crockford, -1) != self::checksum($base10)) {
            throw new RuntimeException(sprintf(
                'Invalid crockford checksum for {%s}: found "%s" must be "%s"',
                $number,
                substr($crockford, -1),
                self::checksum($base10)
            ));
        }

        // Convert to desired base
        return Base::convert($base10, 10, $base);
    }

    /**
     * Calculate crockford checksum for number
     *
     * @param mixed $number Number (base-10)
     *
     * @return string The Crockford encoded checksum
     */
    protected static function checksum($number)
    {
        return substr(self::CROCKFORD, bcmod($number, 37), 1);
    }
}
