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

use Ocubom\Math\Base\Binary;
use Ocubom\Math\Base\Numeric;

/**
 * Base convert.
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
abstract class Base
{
    private static $bases = [
        'bin' => Binary::class,
        'binary' => Binary::class,
    ];

    /**
     * Convert a number between arbitrary bases.
     *
     * @param int|string      $number The number to convert. Any invalid characters in num are silently ignored. As of PHP 7.4.0 supplying any invalid characters is deprecated.
     * @param int|string|Base $source The base number is in
     * @param int|string|Base $target The base to convert num to
     *
     * @return string
     */
    final public static function convert($number, $source, $target)
    {
        // Filter bases
        $source = self::filterBase($source);
        $target = self::filterBase($target);
        if ($source == $target) {
            return (is_numeric($number) ? strval($number) : $number) ?: '0';
        }

        // Filter number with source base
        $number = $source->filterValue($number);

        // Do base conversions
        $number = self::fromBase($number, $source->getMap());
        $number = self::toBase($number, $target->getMap());

        // Prepare the final value
        return $target->returnValue($number);
    }

    /**
     * Filter & validate base.
     *
     * @param string|int|BaseInterface $base
     *
     * @return BaseInterface
     *
     * @throws \ReflectionException
     */
    final public static function filterBase($base)
    {
        if ($base instanceof BaseInterface) {
            return $base;
        }

        if ($class = self::$bases[strtolower($base)] ?? null) {
            $class = new \ReflectionClass($class);

            return $class->newInstanceArgs();
        }

        return new Numeric($base);
    }

    /**
     * The following method was extracted from symfony/uid (v6.1.4).
     *
     * Code subject to the MIT license (https://github.com/symfony/symfony/blob/v6.1.4/src/Symfony/Component/Uid/LICENSE)
     *
     * Copyright (c) 2020-2022 Fabien Potencier
     *
     * @see https://github.com/symfony/symfony/blob/v6.1.4/src/Symfony/Component/Uid/BinaryUtil.php#L74
     */
    final private static function fromBase($digits, $map)
    {
        $base = \strlen($map['']);
        $count = \strlen($digits);
        $bytes = [];

        while ($count) {
            $quotient = [];
            $remainder = 0;

            for ($i = 0; $i !== $count; ++$i) {
                $carry = ($bytes ? $digits[$i] : $map[$digits[$i]]) + $remainder * $base;

                if (\PHP_INT_SIZE >= 8) {
                    $digit = $carry >> 16;
                    $remainder = $carry & 0xFFFF;
                } else {
                    $digit = $carry >> 8;
                    $remainder = $carry & 0xFF;
                }

                if ($digit || $quotient) {
                    $quotient[] = $digit;
                }
            }

            $bytes[] = $remainder;
            $count = \count($digits = $quotient);
        }

        return pack(\PHP_INT_SIZE >= 8 ? 'n*' : 'C*', ...array_reverse($bytes));
    }

    /**
     * The following method was extracted from symfony/uid (v6.1.4).
     *
     * Code subject to the MIT license (https://github.com/symfony/symfony/blob/v6.1.4/src/Symfony/Component/Uid/LICENSE)
     *
     * Copyright (c) 2020-2022 Fabien Potencier
     *
     * @see https://github.com/symfony/symfony/blob/v6.1.4/src/Symfony/Component/Uid/BinaryUtil.php#L47
     */
    final private static function toBase($bytes, $map)
    {
        $base = \strlen($alphabet = $map['']);
        $bytes = array_values(unpack(\PHP_INT_SIZE >= 8 ? 'n*' : 'C*', $bytes));
        $digits = '';

        while ($count = \count($bytes)) {
            $quotient = [];
            $remainder = 0;

            for ($i = 0; $i !== $count; ++$i) {
                $carry = $bytes[$i] + ($remainder << (\PHP_INT_SIZE >= 8 ? 16 : 8));
                $digit = intdiv($carry, $base);
                $remainder = $carry % $base;

                if ($digit || $quotient) {
                    $quotient[] = $digit;
                }
            }

            $digits = $alphabet[$remainder].$digits;
            $bytes = $quotient;
        }

        return $digits;
    }
}
