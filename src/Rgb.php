<?php

namespace Abivia\ColorSpace;

use Abivia\ColorSpace\Color;

class Rgb extends Color
{
    /**
     * @param array|float|int|string|Color $red
     * @param float|int $green
     * @param float|int $blue
     * @param float $alpha
     * @throws ColorSpaceException
     */
    public function __construct(
        array|float|int|string|Color $red = 0.0,
        float|int $green = 0.0,
        float|int $blue = 0.0,
        float $alpha = 1.0
    )
    {
        /*
         * If this is the first time we're constructing an object, initialize
         * the hue delta factors.
         */
        if ($red instanceof Color) {
            parent::__construct($red);
        } else {
            parent::__construct();
            $this->setRgba($red, $green, $blue, $alpha);
        }
    }

    /**
     * Color RGB factory; create a colorspace based on variable parameters.
     *
     * @param float|array|int|string|Color $red If an array is provided,
     * the array elements are decomposed to scalars (up to the maximum
     * defined) and handled as scalars. If a numeric value is provided, this
     * is the red component of the color. Integers are treated as being in the
     * range 0-255; floats in the range 0.0-1.0. String values are parsed as
     * hexadecimal RGB values. Color values are effectively cloned.
     * Defaults to integer zero.
     * @param float|int $green The green component of the color. Integer 0-255 or
     * float 0.0-1.0. Defaults to zero.
     * @param float|int $blue The blue component of the color. Integer 0-255 or
     * float 0.0-1.0. Defaults to zero.
     * @param float|null $alpha The alpha component of the color, range 0.0-1.0. Defaults to 1.0.
     * @return Color The new Color object.
     * @throws ColorSpaceException
     */
    static function factory(
        float|Color|array|int|string $red = 0,
        float|int $green = 0,
        float|int $blue = 0,
        ?float $alpha = null
    ): Color
    {
        if (is_array($red)) {
            while (count($red) < 3) {
                $red[] = 0.0;
            }
            if (count($red) === 3) {
                $red[] = 1.0;
            }
            list($red, $green, $blue, $alpha) = $red;
            $color = new Rgb($red, $green, $blue, $alpha);
        } elseif ($red instanceof Color) {
            $color = new Rgb(
                $red->getRed(), $red->getGreen(), $red->getBlue(), $red->getAlpha()
            );
        } elseif (is_string($red)) {
            $color = new Rgb();
            $red = strtolower($red);
            if (in_array($red, self::$namedColors)) {
                $color->setNamedColor($red);
                if ($alpha !== null) {
                    $color->setAlpha($alpha);
                }
            } elseif (preg_match('!#?[0-9a-f]{3}([0-9a-f]{3})?!', $red)) {
                $color->setHex($red);
            } elseif (preg_match('!\s*([a-z]+)\s*\((.*?)\)!', $red, $match)) {
                $color = parent::parseCss($match[1], $match[2]);
            }
        } else {
            $color = new Rgb($red, $green, $blue, $alpha);
        }
        return $color;
    }

    public function toCss(bool $legacy = false): string
    {
        $hasAlpha = $this->alpha !== 1.0;
        $delimit = $legacy ? ', ' : ' ';
        $result = $hasAlpha ? 'rgba(' : 'rgb(';
        $result .= round($this->red * 255)
            . $delimit . round($this->green * 255)
            . $delimit . round($this->blue * 255);
        if ($hasAlpha) {
            $result .= ($legacy ? ', ' : ' / ') . round($this->alpha, 4);
        }
        $result .= ')';

        return $result;
    }

}
