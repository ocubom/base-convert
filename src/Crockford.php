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
    final public static function encode($number, $base, $checksum = false)
    {
        return Base::convert($number, $base, new BaseCrockford($checksum));
    }

    final public static function decode($number, $base, $checksum = false)
    {
        return Base::convert($number, new BaseCrockford($checksum), $base);
    }
}
