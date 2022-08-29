<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math\Tests\Base;

use Ocubom\Math\Base\Base58;
use Ocubom\Math\Tests\TestCase;

class Base58Test extends TestCase
{
    /**
     * @dataProvider provideNativeBaseConvert
     */
    public function testConversionBetweenBases($number1, $base1, $number2, $base2, $insensitive = false)
    {
        $this->doTestConversionBetweenBases($number1, $base1, $number2, $base2, $insensitive);
    }

    public function provideNativeBaseConvert()
    {
        return $this->generateCombinations(__FUNCTION__, function () {
            yield ['5pA', new Base58()];

            for ($base = 2; $base < 36; ++$base) {
                yield [\base_convert(16191, 10, $base), $base];
            }

            yield [hex2bin(dechex(16191)), 'bin'];
        });
    }
}
