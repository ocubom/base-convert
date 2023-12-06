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

use Ocubom\Math\Base\Crockford;
use Ocubom\Math\Exception\InvalidArgumentException;
use Ocubom\Math\Tests\TestCase;

use function Ocubom\Math\crockford_decode;
use function Ocubom\Math\crockford_encode;

class CrockfordTest extends TestCase
{
    /** @dataProvider provideCrockford */
    public function testCrockfordEncode($crockford, $number, $base, $checksum)
    {
        $this->assertEquals($crockford, crockford_encode($number, $base, $checksum));

        // Convert crockford -> number
        $value = crockford_decode($crockford, $base, $checksum);
        $this->assertEquals($number, $value);

        // Ignore hypens
        $crockford = chunk_split($crockford, 3, '-');
        $value = crockford_decode($crockford, $base, $checksum);
        $this->assertEquals($number, $value);
    }

    /** @dataProvider provideCrockford */
    public function testCrockfordDecode($crockford, $number, $base, $checksum)
    {
        // Convert crockford -> number
        $this->assertEquals($number, crockford_decode($crockford, $base, $checksum));
    }

    /** @dataProvider provideCrockford */
    public function testIgnoreHypens($crockford, $number, $base, $checksum)
    {
        // Force hypens
        $crockford = chunk_split($crockford, mt_rand(2, 5), '-');
        $this->assertEquals($number, crockford_decode($crockford, $base, $checksum));
    }

    public function testInvalidChecksumInNumberMustThrownException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid crockford checksum for "01", found "1" must be "0"');

        crockford_decode('01', 10, true);
    }

    public function testInvalidDigitsInNumberMustThrownException()
    {
        $number = Crockford::MAP[''].'-(/)-'.strtolower(Crockford::MAP['']);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Found crockford invalid characters "()/" on number');

        crockford_decode($number, 10, false);
    }

    public function testInvalidChecksumCharacterMustThrownException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid crockford check symbol "%" found');

        crockford_decode('0%', 10, true);
    }

    /**
     * Crockford.
     *
     * Based on http://git.io/jMnYXg
     */
    public function provideCrockford()
    {
        yield ['0',     '0',      10, false];
        yield ['00',    '0',      10, true];

        yield ['1',     '1',      10, false];
        yield ['11',    '1',      10, true];

        yield ['62',    '302',     8, false];
        yield ['629',   '302',     8, true];

        yield ['DY2N',  '456789', 10, false];
        yield ['DY2NR', '456789', 10, true];

        yield ['C515',  '61425',  16, false];
        yield ['C515Z', '61425',  16, true];

        yield ['FVCK',  'frcj',   32, false];
        yield ['FVCKH', 'frcj',   32, true];

        yield ['ZPT7',  'm9tz',   36, false];
        yield ['ZPT7Y', 'm9tz',   36, true];

        // Big numbers safety
        yield ['3D2ZQ6TVC93',  '3838385658376483', 10, false];
        yield ['3D2ZQ6TVC935', '3838385658376483', 10, true];
    }
}
