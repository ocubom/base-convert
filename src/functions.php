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

/**
 * Convert a number between arbitrary bases.
 *
 * Supports large arbitrary numbers by using a safe php implementation
 *
 * @see http://php.net/manual/en/function.base-convert.php
 * @see http://php.net/manual/en/function.base-convert.php#109660
 *
 * @param int|string               $number The number to convert
 * @param BaseInterface|int|string $source Original base for number
 * @param BaseInterface|int|string $target Desired base for number
 */
function base_convert($number, $source, $target): string
{
    return Base::convert($number, $source, $target);
}

/**
 * Convert a number to crockford base32 encoding.
 *
 * @param int|string               $number   The number to convert
 * @param BaseInterface|int|string $base     The base of $number
 * @param bool                     $checksum Include a checksum
 */
function crockford_encode($number, $base, bool $checksum = false): string
{
    return Crockford::encode($number, $base, $checksum);
}

/**
 * Convert a number from crockford base32 encoding.
 *
 * @param int|string               $number   The crockford number to convert
 * @param BaseInterface|int|string $base     The base to convert $number
 * @param bool                     $checksum Includes $number a checksum?
 */
function crockford_decode($number, $base, bool $checksum = false): string
{
    return Crockford::decode($number, $base, $checksum);
}
