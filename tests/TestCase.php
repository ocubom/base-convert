<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math\Tests;

use function Ocubom\Math\base_convert;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected static function toText($number, $base)
    {
        return sprintf(
            '%s::%s',
            ('bin' === (string) $base) ? '"'.base64_encode($number).'"' : (string) $number,
            (string) $base
        );
    }

    protected function doTestConversionBetweenBases($number1, $base1, $number2, $base2, $insensitive = false)
    {
        $method = $insensitive ? 'assertEqualsIgnoringCase' : 'assertEquals';
        $this->{$method}($number2, base_convert($number1, $base1, $base2));
    }

    /**
     * Base conversions.
     *
     * @return iterable
     */
    protected function generateCombinations($name, $numbers)
    {
        $items = is_callable($numbers) ? $numbers() : $numbers;
        $items = $items instanceof \Traversable ? iterator_to_array($items) : (array) $items;
        $count = count($items);

        for ($idx1 = 0; $idx1 < $count; ++$idx1) {
            $item1 = $items[$idx1][0];
            $base1 = $items[$idx1][1];
            $case1 = $items[$idx1][2] ?? false;
            $text1 = self::toText($item1, $base1);

            for ($idx2 = 0; $idx2 < $count; ++$idx2) {
                $item2 = $items[$idx2][0];
                $base2 = $items[$idx2][1];
                $case2 = $items[$idx2][2] ?? false;
                $text2 = self::toText($item2, $base2);

                $key = trim(sprintf(
                    '%s: %s -> %s %s',
                    $name,
                    $text1 ?? '',
                    $text2 ?? '',
                    $case1 || $case2 ? '(insensitive)' : ''
                ));

                yield $key => [
                    $item1,
                    $base1,
                    $item2,
                    $base2,
                    $case1 || $case2,
                ];
            }
        }
    }
}
