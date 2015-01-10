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
        // No base change requested
        if ($frombase === $tobase) {
            return $number;
        }

        // Check special base for binary numbers
        if ('bin' === $frombase) {
            $number   = bin2hex($number);
            $frombase = 16;
        }
        $cleanfn = null; // "do-nothing"
        if ('bin' === $tobase) {
            $cleanfn = 'hex2bin';
            $tobase  = 16;
        }

        // Clean argument
        $number = trim($number);

        // Do not convert same base
        if (intval($frombase) === intval($tobase)) {
            return empty($cleanfn) ? $number : $cleanfn($number);
        }

        // Convert value to base-10 if needed
        $base10 = $number;
        if (intval($frombase) != 10) {
            $len    = strlen($number);
            $base10 = 0;

            for ($i = 0; $i < $len; $i++) {
                $digit  = base_convert($number[$i], $frombase, 10);
                $base10 = bcadd(bcmul($base10, $frombase), $digit);
            }
        }

        // Convert value from base-10 if needed
        $result = $base10;
        if (intval($tobase) != 10) {
            $result = '';
            while (bccomp($base10, '0', 0) > 0) {
                $module = intval(bcmod($base10, $tobase));
                $result = base_convert($module, 10, $tobase) . $result;
                $base10 = bcdiv($base10, $tobase, 0);
            }

            if (empty($result)) {
                $result = '0';
            }
        }

        // Postprocess result
        return empty($cleanfn) ? $result : $cleanfn($result);
    }
}
