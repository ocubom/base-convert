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

interface BaseInterface
{
    /**
     * Provides the map for conversion.
     *
     * The format is the same used in symfony/uid component.
     *
     * @see https://github.com/symfony/symfony/blob/v6.1.4/src/Symfony/Component/Uid/BinaryUtil.php
     *
     * @return array
     */
    public function getMap();

    /**
     * Validate the value against the base.
     *
     * @param string|int $number The value to check
     *
     * @return string The filtered valid value
     */
    public function filterValue($number);

    /**
     * Normalize the value to return.
     *
     * @param string|int $value the value to return
     *
     * @return string The normalized value
     */
    public function returnValue($value);

    /**
     * @return string
     */
    public function __toString();
}
