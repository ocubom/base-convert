<?php

/*
 * This file is part of ocubom/base-convert
 *
 * © Oscar Cubo Medina <https://ocubom.github.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!interface_exists('Stringable', false)) {
    /**
     * The Stringable interface denotes a class as having a __toString() method.
     *
     * @since 8.0
     */
    interface Stringable
    {
        /**
         * Gets a string representation of the object.
         *
         * Magic method {@see https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
         * allows a class to decide how it will react when it is treated like a string.
         *
         * @return string returns the string representation of the object
         */
        public function __toString(): string;
    }
}
