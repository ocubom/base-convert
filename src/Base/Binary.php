<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ocubom\Math\Base;

use Ocubom\Math\Exception\RuntimeException;

class Binary extends Numeric
{
    public function __construct()
    {
        parent::__construct(16); // Uses Hex as internal format to use hex2bin and bin2hex functions
    }

    public function filterValue($value): string
    {
        return bin2hex((string) $value);
    }

    public function returnValue($value): string
    {
        // Must be left padded to force even length
        $len = strlen($value);
        $val = str_pad($value, $len + ($len % 2), '0', \STR_PAD_LEFT);

        // Perform conversion
        $val = \hex2bin($val);
        if (false === $val) {
            throw new RuntimeException(sprintf('Unable to convert "%s" to binary', $value)); // @codeCoverageIgnore
        }

        // Wipe unneeded starting 0
        return ltrim($val, "\0");
    }
}
