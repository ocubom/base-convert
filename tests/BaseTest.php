<?php

/*
 * This file is part of "Base Convert" component.
 *
 * © Oscar Cubo Medina <ocubom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math\Tests;

use Ocubom\Math\Base;

/**
 * Test BigNumber base convert utility functions
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
class BaseTest extends TestCase
{
    // Indexes
    const NUMBER = 0;
    const BASE   = 1;

    /**
     * Base conversion
     *
     * @param array $numbers An array of number representation and its base
     *
     * @dataProvider provideConvertNumbersAndBases
     */
    public function testConvert($numbers)
    {
        $numbers = func_get_args();

        for ($i = 0; $i < count($numbers); $i++) {
            for ($j = 0; $j < count($numbers); $j++) {
                $this->assertEquals(
                    $numbers[$j][self::NUMBER],
                    Base::convert(
                        $numbers[$i][self::NUMBER],
                        $numbers[$i][self::BASE],
                        $numbers[$j][self::BASE]
                    ),
                    sprintf(
                        '(%d, %d): %s::base(%s) -> %s::base(%s)',
                        $i,
                        $j,
                        $numbers[$i][self::NUMBER],
                        $numbers[$i][self::BASE],
                        $numbers[$j][self::NUMBER],
                        $numbers[$j][self::BASE]
                    )
                );
            }
        }
    }

    /**
     * Empty numbers must return 0 value
     */
    public function testEmptyNumberMustReturnZero()
    {
        $this->assertEquals('0', Base::convert('', 32, 10), 'Empty string');
        $this->assertEquals('0', Base::convert(null, 32, 16), 'Null value');
    }

    /**
     * Invalid bases (<2) must generate an exception
     */
    public function testInvalidFromBaseLower()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Invalid "from base": must be in the range [2-36] but found "0"'
        );

        Base::Convert('', 0, 10);
    }

    /**
     * Invalid bases (>36) must generate an exception
     */
    public function testInvalidFromBaseUpper()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Invalid "from base": must be in the range [2-36] but found "37"'
        );

        Base::Convert('', 37, 10);
    }

    /**
     * Invalid bases (<2) must generate an exception
     */
    public function testInvalidToBaseLower()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Invalid "to base": must be in the range [2-36] but found "0"'
        );

        Base::Convert('', 10, 0);
    }

    /**
     * Invalid bases (>36) must generate an exception
     */
    public function testInvalidToBaseUpper()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Invalid "to base": must be in the range [2-36] but found "37"'
        );

        Base::Convert('', 10, 37);
    }

    /**
     * Only number, hex or bin from bases are allowed
     */
    public function testUnknownFromBase()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Unknown base "notvalid"'
        );

        Base::Convert('', 'notvalid', 10);
    }

    /**
     * Only number, hex or bin to bases are allowed
     */
    public function testUnknownToBase()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Unknown base "notvalid"'
        );

        Base::Convert('', 10, 'notvalid');
    }

    /**
     * Invalid characters on number must generate an exception
     */
    public function testInvalidNumberBase()
    {
        $this->setExpectedException(
            '\\RuntimeException',
            'Found invalid characters "ghi" for base-16 on number "2i4h1a3g0"'
        );

        Base::Convert('2i4h1a3g0', 16, 36);
    }

    /**
     * Base conversions
     *
     * @return array
     */
    public function provideConvertNumbersAndBases()
    {
        // Generate a random number
        do {
            $rand = mt_rand();
        } while (strlen(dechex($rand)) % 2);

        return array(
            array(
                array('0', 2),
                array('0', 16),
                array('0', 32),
            ),
            array(
                array('101', 2),
                array('12', 3),
                array('10', 5),
                array('5', 6),
                array('5', 8),
                array('5', 16),
                array('5', 32),
            ),
            // Must return the same values than native functions
            array(
                array($rand, 'dec'),
                array(decbin($rand), 2),
                array(decoct($rand), 'oct'),
                array(dechex($rand), 'hex'),
                array(hex2bin(dechex($rand)), 'bin'),
            ),
            // Random UUID: C3E51D3E-996C-11E4-9FF0-73AF89BEA664
            array(
                array('C3E51D3E996C11E49FF073AF89BEA664', 16),
                array('c3e51d3e996c11e49ff073af89bea664', 16),
                array('260389088308169507514803957383785522788', 10),
                array('63skejt6bc27i9vs3jlu4rt9j4', 32),
                array('bliaxymsslver9jecsthunzok', 36),
            ),
            array(
                array('1000000000000', 10),
                array('t3aaa400', 32),
                array('cre66i9s', 36),
            ),
            array(
                array('3641100', 8),
                array('1000000', 10),
                array('f4240', 16),
                array('lfls', 36),
            ),
        );
    }
}
