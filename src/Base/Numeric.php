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

use Ocubom\Math\AbstractBase;
use Ocubom\Math\Exception\InvalidArgumentException;

/**
 * Numeric bases from 2 to 62.
 */
class Numeric extends AbstractBase
{
    /**
     * Base62.
     *
     * @see https://en.wikipedia.org/wiki/Base62
     */
    const SYMBOLS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * Bases with special names.
     *
     * @var array
     */
    const NAMED_BASES = [
        'bin' => 2,
        'dec' => 10,
        'hex' => 16,
        'oct' => 8,
    ];

    const OPTIONS = ['options' => [
        'min_range' => 2,
        'max_range' => 62,
    ]];

    /**
     * Base value.
     *
     * @var int
     */
    private $value;

    private static $maps = [];

    /**
     * @param int|string $base
     */
    public function __construct($base)
    {
        $this->value = filter_var(
            self::NAMED_BASES[strtolower($base)] ?? $base,
            \FILTER_VALIDATE_INT,
            self::OPTIONS
        );

        if (false === $this->value) {
            throw new InvalidArgumentException("Invalid base ({$base}), must be in the range [2-62]");
        }
    }

    public function getMap(): array
    {
        if (!array_key_exists($this->value, self::$maps)) {
            // Extract valid symbols
            $symbols = substr(self::SYMBOLS, 0, $this->value);

            // Create reverse mapping
            $mapping = str_split($symbols);
            $mapping = array_combine(array_values($mapping), array_keys($mapping));

            // For bases up to 36, case is ignored
            if ($this->value <= 36) {
                // User lowercase letters for compatibility with native base_convert
                $symbols = strtolower($symbols);

                $lowers = str_split($symbols);
                $lowers = array_combine(array_values($lowers), array_keys($lowers));

                $mapping = array_merge($mapping, array_slice($lowers, 10));
            }

            // Add special symbols entry
            $mapping[''] = $symbols;

            self::$maps[$this->value] = $mapping;
        }

        return self::$maps[$this->value];
    }

    public function __toString(): string
    {
        return 'Base'.$this->value;
    }
}
