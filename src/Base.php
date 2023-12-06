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
use Ocubom\Math\Exception\InvalidArgumentException;

/**
 * Base convert.
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
abstract class Base
{
    /** @var class-string[] */
    private static $bases = [
        'bin' => Binary::class,
        'binary' => Binary::class,
    ];

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
    final public static function convert($number, $source, $target): string
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
     * @param BaseInterface|string|int $base
     */
    final public static function filterBase($base): BaseInterface
    {
        if ($base instanceof BaseInterface) {
            return $base;
        }

        if ($class = self::$bases[strtolower((string) $base)] ?? null) {
            try {
                $object = (new \ReflectionClass($class))->newInstance();

                if ($object instanceof BaseInterface) {
                    return $object;
                }
            } catch (\ReflectionException $exc) { // @codeCoverageIgnore
                throw new InvalidArgumentException("Invalid base ({$base})", 0, $exc); // @codeCoverageIgnore
            }
        }

        return new Numeric((string) $base);
    }

    /**
     * The following method was extracted from symfony/uid (v7.0.1).
     *
     * Code subject to the MIT license (https://github.com/symfony/symfony/blob/v7.0.1/src/Symfony/Component/Uid/LICENSE)
     *
     * Copyright (c) 2020-2022 Fabien Potencier
     *
     * @see https://github.com/symfony/symfony/blob/v7.0.1/src/Symfony/Component/Uid/BinaryUtil.php#L74
     */
    public static function fromBase(string $digits, array $map): string
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
     * The following method was extracted from symfony/uid (v7.0.1).
     *
     * Code subject to the MIT license (https://github.com/symfony/symfony/blob/v7.0.1/src/Symfony/Component/Uid/LICENSE)
     *
     * Copyright (c) 2020-2022 Fabien Potencier
     *
     * @see https://github.com/symfony/symfony/blob/v7.0.1/src/Symfony/Component/Uid/BinaryUtil.php#L47
     */
    public static function toBase(string $bytes, array $map): string
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
