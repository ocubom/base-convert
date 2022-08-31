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

use Ocubom\Math\Exception\InvalidArgumentException;

abstract class AbstractBase implements BaseInterface
{
    public function filterValue($value): string
    {
        // Force value as string representation of the number
        $number = (is_numeric($value) ? strval($value) : $value) ?: '0';

        // Check if some digit is invalid
        $digits = str_split($number);
        $invalid = array_diff_key(
            array_combine($digits, $digits),
            $this->getMap()
        );

        if (!empty($invalid)) {
            uksort($invalid, 'strnatcasecmp');

            throw new InvalidArgumentException(sprintf(
                'Found %s invalid characters "%s" on number "%s"',
                (string) $this,
                implode('', array_keys($invalid)),
                $number
            ));
        }

        return $number;
    }

    public function returnValue($value): string
    {
        return (is_numeric($value) ? strval($value) : $value) ?: '0';
    }

    public function __toString(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }
}
