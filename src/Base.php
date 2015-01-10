<?php

/*
 * This file is part of "Base Convert" component.
 *
 * © Oscar Cubo Medina <ocubom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math;

use \RuntimeException;

/**
 * BigNumber base convert encoders
 *
 * @author Oscar Cubo Medina <ocubom@gmail.com>
 */
abstract class Base
{
    /**
     * Safe convert of numbers using BC math extension
     *
     * {@see http://www.php.net/manual/es/function.base-convert.php#109660}
     *
     * @param mixed $number   Number to convert
     * @param mixed $frombase Original base for number
     * @param mixed $tobase   Desired base for number
     *
     * @return mixed Number in desired base (string or binary)
     */
    public static function convert($number, $frombase = 10, $tobase = 36)
    {
        if ($frombase == $tobase) {
            return $number;  // No conversion needed
        }

        // Get pre & post conversion process
        list($prefn,  $frombase) = self::cleanFromBase($frombase);
        list($postfn, $tobase)   = self::cleanToBase($tobase);

        // Perform conversion
        $result = $prefn($number);
        if (intval($frombase) != intval($tobase)) {
            $result = self::convertFromBase10(self::convertToBase10($result, $frombase), $tobase);
        }
        $result = $postfn($result);

        return empty($result) ? '0' : $result;
    }

    /**
     * Clean source number base
     *
     * @param mixed $base Source base
     *
     * @return array Pre-process function and returned base of the function
     */
    protected static function cleanFromBase($base)
    {
        if ('bin' === $base) {
            return array('bin2hex', 16);
        }

        return array('trim', $base);
    }

    /**
     * Clean target number base
     *
     * @param mixed $base Target base
     *
     * @return array Post-process function and input base of the function
     */
    protected static function cleanToBase($base)
    {
        if ('bin' === $base) {
            return array('hex2bin', 16);
        }

        return array('trim', $base);
    }

    /**
     * Convert a number from base10 to the desired base
     *
     * @param mixed $number The number to convert in base10
     * @param mixed $base   The new base
     *
     * @return mixed $number converted to $base
     */
    protected static function convertFromBase10($number, $base)
    {
        if (intval($base) == 10) {
            return empty($number) ? '0' : $number; // No conversion needed
        }

        $value = '';
        while (bccomp($number, '0', 0) > 0) {
            $module = intval(bcmod($number, $base));
            $value  = base_convert($module, 10, $base) . $value;
            $number = bcdiv($number, $base, 0);
        }

        return empty($value) ? '0' : $value;
    }

    /**
     * Convert the number from its base to base10
     *
     * @param mixed $number The number to convert
     * @param mixed $base   The base of the number
     *
     * @return mixed $number converted to base10
     */
    protected static function convertToBase10($number, $base)
    {
        if (intval($base) == 10) {
            return empty($number) ? '0' : $number; // No conversion needed
        }

        $value = 0;
        for ($i = 0; $i < strlen($number); $i++) {
            $digit = base_convert($number[$i], $base, 10);
            $value = bcadd(bcmul($value, $base), $digit);
        }

        return empty($value) ? '0' : $value;
    }
}
