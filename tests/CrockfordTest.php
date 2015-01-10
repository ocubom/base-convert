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

use Ocubom\Math\Crockford;

/**
 * Test Crockford Base32 encoder/decoder
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
class CrockfordTest extends TestCase
{
    /**
     * Check Crodford base64 encoder
     *
     * @param string  $crockford Crodford encoded number
     * @param string  $number    Original umber
     * @param integer $base      Base for $number
     * @param bool    $checksum  Include checksum
     *
     * @dataProvider provideCrockford
     */
    public function testCrockford($crockford, $number, $base, $checksum)
    {
        // Convert number -> crockford
        $value = Crockford::encode($number, $base, $checksum);
        $this->assertEquals($crockford, $value);

        // Convert crockford -> number
        $value = Crockford::decode($crockford, $base, $checksum);
        $this->assertEquals($number, $value);

        // Ignore hypens
        $crockford = chunk_split($crockford, 3, '-');
        $value = Crockford::decode($crockford, $base, $checksum);
        $this->assertEquals($number, $value);
    }

    /**
     * Invalid checksum must generate an exception
     */
    public function testCrockfordException()
    {
        // Invalid crockford number => throw an exception
        $this->setExpectedException(
            '\\RuntimeException',
            'Invalid crockford checksum for {01}: found "1" must be "0"'
        );

        Crockford::decode('01', 10, true);
    }

    /**
     * Crockford.
     *
     * Based on http://git.io/jMnYXg
     *
     * @return array
     */
    public function provideCrockford()
    {
        return array(
            array('0',     '0',      10, false),
            array('00',    '0',      10, true),
            array('1',     '1',      10, false),
            array('11',    '1',      10, true),
            array('62',    '302',     8, false),
            array('629',   '302',     8, true),
            array('DY2N',  '456789', 10, false),
            array('DY2NR', '456789', 10, true),
            array('C515',  '61425',  16, false),
            array('C515Z', '61425',  16, true),
            array('FVCK',  'frcj',   32, false),
            array('FVCKH', 'frcj',   32, true),
            array('ZPT7',  'm9tz',   36, false),
            array('ZPT7Y', 'm9tz',   36, true),
            // Big numbers safety
            array('3D2ZQ6TVC93',  '3838385658376483', 10, false),
            array('3D2ZQ6TVC935', '3838385658376483', 10, true),
        );
    }
}
