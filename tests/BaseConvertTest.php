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

use Ocubom\Math\Base;
use Ocubom\Math\Exception\InvalidArgumentException;

use function Ocubom\Math\base_convert;

/**
 * Test BigNumber base convert utility functions.
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
class BaseConvertTest extends TestCase
{
    const MIN_BASE = 2;
    const MAX_BASE = 62;

    public static $bytes;

    public function testEmptyNumberMustReturnZeroInAnyConversion()
    {
        foreach ([0, '', null] as $value) {
            for ($base1 = self::MIN_BASE; $base1 <= self::MAX_BASE; ++$base1) {
                $text = self::toText($value, $base1);
                for ($base2 = self::MIN_BASE; $base2 <= self::MAX_BASE; ++$base2) {
                    $this->assertEquals(
                        '0',
                        base_convert($value, $base1, $base2),
                        sprintf('%s -> %s', $text, self::toText(0, $base2))
                    );
                    $this->assertEquals('0', base_convert(null, $base1, $base2), "null::{$base1} -> 0::{$base2}");
                }
            }
        }
    }

    /**
     * @dataProvider provideNativeBaseConvert
     * @dataProvider provideNativeDecConvert
     * @dataProvider provideGMP
     */
    public function testConversionBetweenBases($number1, $base1, $number2, $base2, $insensitive = false)
    {
        $this->doTestConversionBetweenBases($number1, $base1, $number2, $base2, $insensitive);
    }

    /**
     * @dataProvider provideInvalidBases
     */
    public function testInvalidBaseMustThrownExceptions($number, $source, $target, $invalid)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid base ({$invalid}), must be in the range [2-62]");

        base_convert($number, $source, $target);
    }

    public function testInvalidDigitsInNumberMustThrownException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Found Base16 invalid characters "ghi" on number "2i4h1a3g0"');

        Base::Convert('2i4h1a3g0', 16, 62);
    }

    public function provideInvalidBases()
    {
        yield 'source base <2' => ['', self::MIN_BASE - 1, 10, self::MIN_BASE - 1];
        yield 'target base <2' => ['', 10, self::MIN_BASE - 1, self::MIN_BASE - 1];

        yield 'source base >62' => ['', self::MAX_BASE + 1, 10, self::MAX_BASE + 1];
        yield 'target base >62' => ['', 10, self::MAX_BASE + 1, self::MAX_BASE + 1];

        yield 'source base unknown' => ['', 'unknown', 10, 'unknown'];
        yield 'target base unknown' => ['', 10, 'unknown', 'unknown'];
    }

    public function provideNativeBaseConvert()
    {
        return $this->generateCombinations('native base_convert', function () {
            // Uses a random bytes (with odd length)
            $bytes = self::$bytes ?? self::$bytes = random_bytes(5);
            yield [$bytes, 'bin'];

            $hex = bin2hex($bytes);

            // Must be compatible with base_convert
            for ($base = 2; $base <= 36; ++$base) {
                yield [\base_convert($hex, 16, $base), $base, true];
            }
        });
    }

    public function provideNativeDecConvert()
    {
        return $this->generateCombinations('native dec{bin|oct|hex}', function () {
            // Uses a random bytes (with odd length)
            $bytes = self::$bytes ?? self::$bytes = random_bytes(5);
            yield [$bytes, 'bin'];

            $dec = hexdec(bin2hex($bytes));
            yield [$dec, 'dec'];

            // Must be compatible with native conversion
            yield [decbin($dec), 2];
            yield [decoct($dec), 'oct'];
            yield [dechex($dec), 'hex'];
        });
    }

    public function provideGMP()
    {
        return $this->generateCombinations('UUID in all bases', function () {
            // Random UUID: C3E51D3E-996C-11E4-9FF0-73AF89BEA664
            yield ['C3E51D3E996C11E49FF073AF89BEA664', 16, true]; // Case insensitive test

            // Generated with gmp_generate.php file
            yield ['11000011111001010001110100111110100110010110110000010001111001001001111111110000011100111010111110001001101111101010011001100100', 2];
            yield ['120212002021000011221210101010121022202200220200022101011110002122222010212102202', 3];
            yield ['3003321101310332212112300101321021333300130322332021233222121210', 4];
            yield ['4321133001241140201223001022224221033300233443043212123', 5];
            yield ['15333014535434444433512243133122204445255035554032', 6];
            yield ['2301436261506664002132131366153356146521554205', 7];
            yield ['3037121647646266021711177603472761157523144', 8];
            yield ['16762230157711117282626608334402588125382', 9];
            yield ['260389088308169507514803957383785522788', 10];
            yield ['8472569a4565905882a218561417708866410', 11];
            yield ['44a98246a00ba5bb54a1b169b5883a5bb918', 12];
            yield ['3631040c896b7645a68b2cc132a3035762c', 13];
            yield ['3cc73585aa2bdba8c605300244699215ac', 14];
            yield ['607e49369051698c254ea5957c3157a28', 15];
            yield ['c3e51d3e996c11e49ff073af89bea664', 16];
            yield ['1ed47a000c8d3fb53eg64cc4g4a8db38', 17];
            yield ['5ch90137360eg4hc0455a60gfd4bgd2', 18];
            yield ['128ga2ad64b8hc6bdga74fhab28gi6i', 19];
            yield ['4h00jj24fj80hb16a68dj561cja6j8', 20];
            yield ['13fd8hf2599c3ek20ibi0ek99e35e5', 21];
            yield ['6fldbhdee83ile4b9g3l02al54k60', 22];
            yield ['1lclf0634jmlgib0k1mh62bk863i6', 23];
            yield ['e2mgl7jf7l67gjjh64ieb6habn6k', 24];
            yield ['4h6i07l921cf12cec5if2iofnbbd', 25];
            yield ['1g7j4gahak1kgifl63bdm47091ec', 26];
            yield ['fn2704pla3g8kioi8a4c2hq3nbk', 27];
            yield ['64cjf2k3ljijii78ednhrjr74qc', 28];
            yield ['2dl115j24spicbcqigc7ojefm8l', 29];
            yield ['10lsp3lt86ppq8ghg3702polhg8', 30];
            yield ['dglojko2hujl8i30r99okk4cd5', 31];
            yield ['63skejt6bc27i9vs3jlu4rt9j4', 32];
            yield ['2rjta1lg54c8duw8hovoh1rv0b', 33];
            yield ['1boj26e36d6k5hrsw8xfpa3sa8', 34];
            yield ['ms3ihxv9tjahikrmmyx2u6cnx', 35];
            yield ['bliaxymsslver9jecsthunzok', 36];
            yield ['60BYB2SASD59aF8MHa2BD4I6Z', 37];
            yield ['36EZ0TR9C4IHR9J54E702ZE3I', 38];
            yield ['1R9HXVJ9LQR5Q3L73SD6APV0c', 39];
            yield ['b05OcJI3LOWXL35LWS618VTS', 40];
            yield ['KdV8a48bVB4PXMFRN2N4R1X4', 41];
            yield ['C1fH3HII32D0LUGFGURaDMHQ', 42];
            yield ['70MGCQfYSJWOW3VYaYeEA3IE', 43];
            yield ['45aLO9L9a3PSCb7OTALCUc30', 44];
            yield ['2KeUXAG9f7gCWXaX93e8QHZc', 45];
            yield ['1MHT9LJj0K6MRSeVTcjg9I96', 46];
            yield ['gSMM0T6gKNMN9PMAj7O7E7H', 47];
            yield ['QcihNak4eRlbc9kZYB27NdK', 48];
            yield ['H1VihZmk0FNAAmCOfBlFeU5', 49];
            yield ['Ak3cb8DW4k2d1TVFMS5Y95c', 50];
            yield ['73EXRhl8QQFNboWlGi9SAZ8', 51];
            yield ['4VX4PajjZ9ZOLX4BHmoKWkC', 52];
            yield ['31XNXqiEEFXf9YYc3GBPqo6', 53];
            yield ['20PqblOAHlpcOMmpUMQp5kK', 54];
            yield ['1IhTEgEX2OHGf9UmVX9ONQX', 55];
            yield ['oURbCpDqRWhGs9LiYfrMDC', 56];
            yield ['Ymc9mGLbgNqjpdL3aKuCXu', 57];
            yield ['OB0us465Pg99HlN9qFNflo', 58];
            yield ['GqhiD0viUqagVWwiOMMad9', 59];
            yield ['BqBZDgYbAd1cv4ZpaPtBr8', 60];
            yield ['8NhSRcbsMeyJFBooOrMA8Q', 61];
            yield ['5xeCyOmJXIiEJqpf4WG36a', 62];
            yield [base64_decode('w+UdPplsEeSf8HOvib6mZA=='), 'bin'];
        });
    }
}
