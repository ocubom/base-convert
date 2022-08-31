<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math;

use Ocubom\Math\Base\Crockford as BaseCrockford;

abstract class Crockford
{
    /**
     * Convert a number to crockford base32 encoding.
     *
     * @param int|string               $number   The number to convert
     * @param BaseInterface|int|string $base     The base of $number
     * @param bool                     $checksum Include a checksum
     */
    final public static function encode($number, $base, bool $checksum = false): string
    {
        return Base::convert($number, $base, new BaseCrockford($checksum));
    }

    /**
     * Convert a number from crockford base32 encoding.
     *
     * @param int|string               $number   The crockford number to convert
     * @param BaseInterface|int|string $base     The base to covert $number
     * @param bool                     $checksum Include a checksum
     */
    final public static function decode($number, $base, bool $checksum = false): string
    {
        return Base::convert($number, new BaseCrockford($checksum), $base);
    }
}
