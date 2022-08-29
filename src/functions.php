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
 * @param string|int               $number The number to convert
 * @param string|int|BaseInterface $source Original base for number
 * @param string|int|BaseInterface $target Desired base for number
 *
 * @return string Number in desired base (string or binary)
 */
function base_convert($number, $source, $target)
{
    return Base::convert($number, $source, $target);
}

/**
 * Convert a number to crockford base32 encoding.
 *
 * @param string|int               $number   The number to convert
 * @param string|int|BaseInterface $base     The base of $number
 * @param bool                     $checksum Include a checksum
 *
 * @return string
 */
function crockford_encode($number, $base, $checksum = false)
{
    return Crockford::encode($number, $base, $checksum);
}

/**
 * Convert a number from crockford base32 encoding.
 *
 * @param string|int               $number   The crockford number to convert
 * @param string|int|BaseInterface $base     The base to covert $number
 * @param bool                     $checksum Include a checksum
 *
 * @return int|string
 */
function crockford_decode($number, $base, $checksum = false)
{
    return Crockford::decode($number, $base, $checksum);
}
