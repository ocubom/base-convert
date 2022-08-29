<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$number = gmp_init('C3E51D3E996C11E49FF073AF89BEA664', 16);
for ($base = 2; $base < 63; ++$base) {
    printf("yield ['%s', %d];\n", gmp_strval($number, $base), $base);
}

printf("yield [base64_decode('%s'), '%s'];\n", base64_encode(hex2bin(gmp_strval($number, 16))), 'bin');
