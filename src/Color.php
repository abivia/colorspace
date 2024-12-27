<?php

namespace Abivia\ColorSpace;

/**
 * Multiple model representation of a color.
 *
 * The Color class provides for the manipulation of colors using both
 * the RGB and HSL models.
 *
 * The class incorporates information on human visual perception to provide
 * functions that help indicate how different a human will perceive colors to
 * be.
 *
 */
abstract class Color
{
    /**
     * @var float Alpha channel, common to all models, ranges 0 to 1.
     */
    protected float $alpha = 0.0;

    /**
     * @var float Red, a component of the RGB model, ranges 0 to 1.
     */
    protected float $blue = 0.0;
    /**
     * @var float Green, a component of the RGB model, ranges 0 to 1.
     */
    protected float $green = 0.0;
    /**
     * Hue Deltas
     *
     * The hue deltas are weighting factors that define how different the human
     * eye perceives a unit change in hue values.
     */
    static protected array $hueDeltas;
    /**
     * @var array Named colors as defined in HTML 4.0, with enhancements.
     */
    protected static array $namedColors = [
        'aliceblue' => 0xfff0f8ff,
        'antiquewhite' => 0xfffaebd7,
        'aqua' => 0xff00ffff,
        'aquamarine' => 0xff7fffd4,
        'azure' => 0xfff0ffff,
        'beige' => 0xfff5f5dc,
        'bisque' => 0xffffe4c4,
        'black' => 0xff000000,
        'blanchedalmond' => 0xffffebcd,
        'blue' => 0xff0000ff,
        'blueviolet' => 0xff8a2be2,
        'brown' => 0xffa52a2a,
        'burlywood' => 0xffdeb887,
        'cadetblue' => 0xff5f9ea0,
        'chartreuse' => 0xff7fff00,
        'chocolate' => 0xffd2691e,
        'clear' => 0x7f000000,
        'coral' => 0xffff7f50,
        'cornflowerblue' => 0xff6495ed,
        'cornsilk' => 0xfffff8dc,
        'crimson' => 0xffdc143c,
        'cyan' => 0xff00ffff,
        'darkblue' => 0xff00008b,
        'darkcyan' => 0xff008b8b,
        'darkgoldenrod' => 0xffb8860b,
        'darkgray' => 0xffa9a9a9,
        'darkgrey' => 0xffa9a9a9,
        'darkgreen' => 0xff006400,
        'darkkhaki' => 0xffbdb76b,
        'darkmagenta' => 0xff8b008b,
        'darkolivegreen' => 0xff556b2f,
        'darkorange' => 0xffff8c00,
        'darkorchid' => 0xff9932cc,
        'darkred' => 0xff8b0000,
        'darksalmon' => 0xffe9967a,
        'darkseagreen' => 0xff8fbc8f,
        'darkslateblue' => 0xff483d8b,
        'darkslategray' => 0xff2f4f4f,
        'darkslategrey' => 0xff2f4f4f,
        'darkturquoise' => 0xff00ced1,
        'darkviolet' => 0xff9400d3,
        'deeppink' => 0xffff1493,
        'deepskyblue' => 0xff00bfff,
        'dimgray' => 0xff696969,
        'dimgrey' => 0xff696969,
        'dodgerblue' => 0xff1e90ff,
        'firebrick' => 0xffb22222,
        'floralwhite' => 0xfffffaf0,
        'forestgreen' => 0xff228b22,
        'fuchsia' => 0xffff00ff,
        'gainsboro' => 0xffdcdcdc,
        'ghostwhite' => 0xfff8f8ff,
        'gold' => 0xffffd700,
        'goldenrod' => 0xffdaa520,
        'gray' => 0xff808080,
        'grey' => 0xff808080,
        'green' => 0xff008000,
        'greenyellow' => 0xffadff2f,
        'honeydew' => 0xfff0fff0,
        'hotpink' => 0xffff69b4,
        'indianred' => 0xffcd5c5c,
        'indigo' => 0xff4b0082,
        'ivory' => 0xfffffff0,
        'khaki' => 0xfff0e68c,
        'lavender' => 0xffe6e6fa,
        'lavenderblush' => 0xfffff0f5,
        'lawngreen' => 0xff7cfc00,
        'lemonchiffon' => 0xfffffacd,
        'lightblue' => 0xffadd8e6,
        'lightcoral' => 0xfff08080,
        'lightcyan' => 0xffe0ffff,
        'lightgoldenrodyellow' => 0xfffafad2,
        'lightgreen' => 0xff90ee90,
        'lightgrey' => 0xffd3d3d3,
        'lightgray' => 0xffd3d3d3,
        'lightpink' => 0xffffb6c1,
        'lightsalmon' => 0xffffa07a,
        'lightseagreen' => 0xff20b2aa,
        'lightskyblue' => 0xff87cefa,
        'lightslategray' => 0xff778899,
        'lightslategrey' => 0xff778899,
        'lightsteelblue' => 0xffb0c4de,
        'lightyellow' => 0xffffffe0,
        'lime' => 0xff00ff00,
        'limegreen' => 0xff32cd32,
        'linen' => 0xfffaf0e6,
        'magenta' => 0xffff00ff,
        'maroon' => 0xff800000,
        'mediumaquamarine' => 0xff66cdaa,
        'mediumblue' => 0xff0000cd,
        'mediumorchid' => 0xffba55d3,
        'mediumpurple' => 0xff9370db,
        'mediumseagreen' => 0xff3cb371,
        'mediumslateblue' => 0xff7b68ee,
        'mediumspringgreen' => 0xff00fa9a,
        'mediumturquoise' => 0xff48d1cc,
        'mediumvioletred' => 0xffc71585,
        'midnightblue' => 0xff191970,
        'mintcream' => 0xfff5fffa,
        'mistyrose' => 0xffffe4e1,
        'moccasin' => 0xffffe4b5,
        'navajowhite' => 0xffffdead,
        'navy' => 0xff000080,
        'navyblue' => 0xff9fafdf,
        'oldlace' => 0xfffdf5e6,
        'olive' => 0xff808000,
        'olivedrab' => 0xff6b8e23,
        'orange' => 0xffffa500,
        'orangered' => 0xffff4500,
        'orchid' => 0xffda70d6,
        'palegoldenrod' => 0xffeee8aa,
        'palegreen' => 0xff98fb98,
        'paleturquoise' => 0xffafeeee,
        'palevioletred' => 0xffdb7093,
        'papayawhip' => 0xffffefd5,
        'peachpuff' => 0xffffdab9,
        'peru' => 0xffcd853f,
        'pink' => 0xffffc0cb,
        'plum' => 0xffdda0dd,
        'powderblue' => 0xffb0e0e6,
        'purple' => 0xff800080,
        'red' => 0xffff0000,
        'rosybrown' => 0xffbc8f8f,
        'royalblue' => 0xff4169e1,
        'saddlebrown' => 0xff8b4513,
        'salmon' => 0xfffa8072,
        'sandybrown' => 0xfff4a460,
        'seagreen' => 0xff2e8b57,
        'seashell' => 0xfffff5ee,
        'sienna' => 0xffa0522d,
        'silver' => 0xffc0c0c0,
        'skyblue' => 0xff87ceeb,
        'slateblue' => 0xff6a5acd,
        'slategray' => 0xff708090,
        'slategrey' => 0xff708090,
        'snow' => 0xfffffafa,
        'springgreen' => 0xff00ff7f,
        'steelblue' => 0xff4682b4,
        'tan' => 0xffd2b48c,
        'teal' => 0xff008080,
        'thistle' => 0xffd8bfd8,
        'tomato' => 0xffff6347,
        'transparent' => 0x00000000,
        'turquoise' => 0xff40e0d0,
        'violet' => 0xffee82ee,
        'wheat' => 0xfff5deb3,
        'white' => 0xffffffff,
        'whitesmoke' => 0xfff5f5f5,
        'yellow' => 0xffffff00,
        'yellowgreen' => 0xff9acd32,
    ];
    /**
     * @var true
     */
    protected bool $recompute = false;

    /**
     * @var float Red, a component of the RGB model, ranges 0 to 1.
     */
    protected float $red = 0.0;

    protected static array $spaceMap = [
        'hsl' => Hsl::class,
        'hsla' => Hsl::class,
        'rgb' => Rgb::class,
        'rgba' => Rgb::class,
    ];

    /**
     * Class constructor.
     */
    public function __construct(?Color $color = null)
    {
        /*
         * If this is the first time we're constructing an object, initialize
         * the hue delta factors.
         */
        self::initializeHueDeltas();
        if ($color) {
            $this->red = $color->getRed();
            $this->green = $color->getGreen();
            $this->blue = $color->getBlue();
            $this->alpha = $color->getAlpha();
            $this->recompute = true;
        }
    }

    /**
     * Add another color to this one.
     *
     * This is useful in computation, e.g. when the delta is an incremental
     * transition between two colors.
     *
     * @param Color $delta The incremental values.
     * @throws ColorSpaceException
     */
    public function add(Color $delta): self
    {
        $this->alpha = self::limit($this->alpha + $delta->getAlpha());
        $this->red = self::limit($this->red + $delta->getRed());
        $this->green = self::limit($this->green + $delta->getGreen());
        $this->blue = self::limit($this->blue + $delta->getBlue());
        $this->recompute = true;

        return $this;
    }

    /**
     * Convert a float to a percentage.
     * @param float $value
     * @param int $places
     * @return string
     */
    public static function asPercent(float $value, int $places = 2): string
    {
        return round($value * 100, $places) . '%';
    }

    /**
     * Calculate an intermediate color from two colors
     *
     * This is useful in computation, e.g. to compute an incremental transition
     * between two.
     * Returns the mix color. Negative values are accepted; the absolute value
     * of this argument is used.
     *
     * @param Color $mix The color to transition to.
     * @param float $ratio Linear point between colors, where 0 is no change and 1.0
     * @return Color The blended color.
     * @throws ColorSpaceException
     */
    public function blend(Color $mix, float $ratio, $blendAlpha = false): Color
    {
        $ratio = self::limit(abs($ratio));
        if ($blendAlpha) {
            $ratioA = $ratio;
        } else {
            $ratio *= (1 - $mix->getAlpha());
            $ratioA = 0;
        }
        /** @var Color $color */
        $color = new (self::class)();
        $color->setRed($this->red + $ratio * ($mix->getRed() - $this->red));
        $color->setGreen($this->green + $ratio * ($mix->getGreen() - $this->green));
        $color->setBlue($this->blue + $ratio * ($mix->getBlue() - $this->blue));
        $color->setAlpha($this->alpha + $ratioA * ($mix->getAlpha() - $this->alpha));
        return $color;
    }

    /**
     * Get difference between two colors
     *
     * This is useful in computation, e.g. to compute an incremental transition
     * between two colors.
     *
     * @param Color $delta The color to transition to.
     * @param int $steps The number of steps in the transition. Optional, defaults to 1.
     * @return Color A color containing the stepwise difference
     * between the two colors.
     * @throws ColorSpaceException
     */
    public function delta(Color $delta, int $steps = 1): Color
    {
        /** @var Color $color */
        $color = new (self::class)();
        $color->setRed(($delta->getRed() - $this->red) / $steps);
        $color->setGreen(($delta->getGreen() - $this->green) / $steps);
        $color->setBlue(($delta->getBlue() - $this->blue) / $steps);
        $color->setAlpha(($delta->getAlpha() - $this->alpha) / $steps);
        return $color;
    }

    /**
     * @param string $css
     * @return Color
     * @throws ColorSpaceException
     */
    public static function fromCss(string $css): Color
    {
        $css = strtolower($css);
        if (isset($css, self::$namedColors[$css])) {
            $color = (new Rgb())->setNamedColor($css);
        } elseif (preg_match('!#[0-9a-f]{3}([0-9a-f]{3})?!', $css)) {
            $color = (new Rgb())->setHex($css);
        } elseif (preg_match('!\s*([a-z]+)\s*\((.*?)\)!', $css, $match)) {
            $color = self::parseCss($match[1], $match[2]);
        } else {
            throw new ColorSpaceException("Could nor parse $css as a CSS Color.");
        }
        return $color;
    }

    /**
     * Get the color's alpha channel as a float.
     * @return float
     */
    public function getAlpha(): float
    {
        return $this->alpha;
    }

    /**
     * Get the color's blue channel as a float.
     * @return float
     */
    public function getBlue(): float
    {
        return $this->blue;
    }

    /**
     * Get the color's blue channel as an int.
     * @return int
     */
    public function getBlueInt(): int
    {
        return (int)round(255 * $this->blue);
    }

    /**
     * Return a monochrome equivalent of the color.
     *
     * This method uses the YIQ color model, returning the Y (luminance)
     * component.
     *
     * @return float
     */
    public function getGray(): float
    {
        return 0.30 * $this->red + 0.59 * $this->green + 0.11 * $this->blue;
    }

    /**
     * Get the color's green channel as a float
     * @return float
     */
    public function getGreen(): float
    {
        return $this->green;
    }

    /**
     * Get the color's green channel as an int
     * @return int
     */
    public function getGreenInt(): int
    {
        return (int)round(255 * $this->green);
    }

    /**
     * Return the hexadecimal equivalent of the color, optionally with alpha
     * value.
     * @param bool $withAlpha
     * @return string
     */
    public function getHex(bool $withAlpha = false): string
    {
        $hex = $this->hex($this->red) . $this->hex($this->green) . $this->hex($this->blue);
        if ($withAlpha) {
            $hex .= $this->hex($this->alpha);
        }
        return $hex;
    }

    /**
     * Get the color's hue as a float
     * @return float
     */
    public function getHue(): float {
        $lightness = max($this->red, $this->green, $this->blue);
        $min = min($this->red, $this->green, $this->blue);
        if ($lightness) {
            $saturation = ($lightness - $min) / $lightness;
        } else {
            $saturation = 0.0;
        }
        if ($saturation) {
            $delta = $lightness - $min;
            if ($this->red === $lightness) {
                $hue = ($this->green - $this->blue) / $delta;
            } else if ($this->green === $lightness) {
                $hue = 2 + ($this->blue - $this->red) / $delta;
            } else {
                $hue = 4 + ($this->red - $this->green) / $delta;
            }
            $hue /= 6;
            if ($hue < 0) {
                $hue += 1.0;
            }
            if ($hue == 1.0) {
                $hue = 0.0;
            }
        } else {
            $hue = 0.0;
        }

        return $hue;
    }

    /**
     * Calculate a normalized hue, on a scale of 0 to 1, using a
     * human perception of color differences in ColorSpaceDeltas.
     *
     * @return float
     */
    public function getHueHuman(): float
    {
        return self::hueToHuman($this->getHue());
    }

    /**
     * Get the color's red channel as a float
     * @return float
     */
    public function getRed(): float
    {
        return $this->red;
    }

    /**
     * Get the color's red channel as an int
     * @return int
     */
    public function getRedInt(): int
    {
        return (int)round(255 * $this->red);
    }

    /**
     * Return the integer equivalent of the color, optionally with alpha value.
     * @param bool $withAlpha
     * @return int
     */
    public function getRgbaInt(bool $withAlpha = true): int
    {
        return self::rgbaToInt(
            $this->red,
            $this->green,
            $this->blue,
            $withAlpha ? $this->alpha : 0
        );
    }

    private function hex(float $value): string
    {
        $int = (int)($value * 255);
        return substr('0' . dechex($int), -2);
    }

    /**
     * Return the relative "distance" between two hues, based on human
     * perception factors.
     * @param Color $color
     * @return float
     */
    public function hueDistance(Color $color): float
    {
        $hd = 2 * abs(self::hueToHuman($this->getHue()) - self::hueToHuman($color->getHue()));
        if ($hd > 1) {
            $hd = 2 - $hd;
        }
        return $hd;
    }

    /**
     * Calculate a normalized hue, on a scale of 0 to 1, using a scale of how
     * humans perceive color differences.
     * @param float $hue
     * @return float
     */
    public static function hueToHuman(float $hue): float
    {
        if ($hue === 0.0) return 0.0;
        self::initializeHueDeltas();
        for ($ind = 0; $ind < count(self::$hueDeltas); ++$ind) {
            if (self::$hueDeltas[$ind][0] >= $hue) {
                $csDelta = self::$hueDeltas[$ind][0] - self::$hueDeltas[$ind - 1][0];
                $hsDelta = $hue - self::$hueDeltas[$ind - 1][0];
                $mcsDelta = self::$hueDeltas[$ind][1] - self::$hueDeltas[$ind - 1][1];
                return self::$hueDeltas[$ind - 1][1] + ($hsDelta / $csDelta * $mcsDelta);
            }
        }
        return 0.0;
    }

    /**
     * Calculate a hue, from a factor representing human perception of color differences.
     * @param float $humanHue
     * @return float
     */
    public static function humanToHue(float $humanHue): float
    {
        if ($humanHue === 0.0) return 0.0;
        self::initializeHueDeltas();
        for ($ind = 0; $ind < count(self::$hueDeltas); ++$ind) {
            if (self::$hueDeltas[$ind][1] >= $humanHue) {
                $csDelta = self::$hueDeltas[$ind][1] - self::$hueDeltas[$ind - 1][1];
                $hsDelta = $humanHue - self::$hueDeltas[$ind - 1][1];
                $mcsDelta = self::$hueDeltas[$ind][0] - self::$hueDeltas[$ind - 1][0];
                return self::$hueDeltas[$ind - 1][0] + ($hsDelta / $csDelta * $mcsDelta);
            }
        }
        return 0.0;
    }

    protected static function initializeHueDeltas(): void
    {
        if (!is_array(self::$hueDeltas ?? false)) {
            // magenta
            // 400, 9.0; 410, 5.5; 420, 2.0; 430, 2.0; 440, 4.0;
            // 450, 4.0; 460, 3.0; 470, 1.2; 480, 0.9; 490, 1.1;
            // 500, 2.0; 510, 3.0; 520, 3.6; 530, 3.8; 540, 3.9;
            // 550, 1.0; 560, 0.8; 570, 0.8; 580, 1.0; 590, 1.7;
            // 600, 1.9; 610, 2.4; 620, 3.1; 630, 4.3; 640, 6.0;
            // 650, 8.0; 660, 11.0;
            // red
            $delta = [9.0, 5.5, 2.0, 2.0, 4.0, 4.0, 3.0, 1.2, 0.9, 1.1,
                2.0, 3.0, 3.6, 3.8, 3.9, 1.0, 0.8, 0.8, 1.0, 1.7,
                1.9, 2.4, 3.1, 4.3, 6.0, 8.0, 11.0];
            self::$hueDeltas = [];
            $step = 1.0 / (count($delta) + 1);
            $d = 0;
            for ($ind = count($delta) - 1, $hStep = 0; $ind >= 0; --$ind, $hStep += $step) {
                self::$hueDeltas[] = [$hStep, $d];
                $d += 1 / $delta[$ind];
            }
            for ($ind = 0; $ind < count(self::$hueDeltas); ++$ind) {
                self::$hueDeltas[$ind][1] /= $d;
            }
            self::$hueDeltas[] = [1.0, 1.0];
        }
    }

    /**
     * Clamp a value into the range 0..1.
     * @param float|int|string $value
     * @param bool $alpha
     * @return float
     * @throws ColorSpaceException
     */
    public static function limit(float|int|string $value, bool $alpha = false): float
    {
        if (is_string($value)) {
            if (str_ends_with($value, '%')) {
                $value = substr($value, 0, -1);
                $factor = 100.0;
            } elseif ($alpha) {
                $factor = 1;
            } else {
                $factor = 255.0;
            }
            if (!is_numeric($value)) {
                throw new ColorSpaceException("$value is not valid.");
            }
            $float = $value / $factor;
        } elseif (is_int($value)) {
            $float = $value / 255.0;
        } else {
            $float = $value;
        }

        if ($float < 0) {
            return 0.0;
        } else if ($float > 1) {
            return 1.0;
        } else {
            return $float;
        }
    }

    /**
     * @param string $function
     * @param string $args
     * @return Color
     * @throws ColorSpaceException
     */
    protected static function parseCss(string $function, string $args): Color
    {
        if (!isset(self::$spaceMap[$function])) {
            throw new ColorSpaceException("Unsupported color function: $function");
        }
        $colorClass = self::$spaceMap[$function];
        // figure out if we're doing "legacy" or "modern" syntax.
        $parts = explode(',', $args);
        if (count($parts) === 1) {
            // Modern syntax, get rid of spurious spaces.
            $args = preg_replace(['!\s+!', '!\s*/\s*!'], [' ', '/'], trim($args));
            $parts = explode(' ', $args);
            if (count($parts) !== 3) {
                throw new ColorSpaceException("Invalid arguments \"$args\" in $function");
            }
            $lastPart = explode('/', $parts[2]);
            if (count($lastPart) > 1) {
                $parts[2] = $lastPart[0];
                $parts[] = $lastPart[1];
            } else {
                $parts[] = 1.0;
            }
            foreach ($parts as $key => &$part) {
                if ($part !== 'none') {
                    $part = self::limit($part, $key === 3);
                }
            }
            $color = new ($colorClass)($parts);
        } elseif (count($parts) >= 3) {
            // Legacy syntax. First, get rid of all spaces.
            $args = preg_replace('!\s*!', '', $args);
            $parts = explode(',', $args);
            foreach ($parts as $key => &$part) {
                $part = self::limit($part, $key === 3);
            }
            if (count($parts) === 3) {
                $parts[] = 1.0;
            }
            $color = new ($colorClass)($parts);
        } else {
            throw new ColorSpaceException("Invalid arguments \"$args\" in $function");
        }

        return $color;
    }

    /**
     * Posterize the color.
     *
     * @param float $quantum A positive, non-zero value. If greater than one, this is
     * considered to be the (rounded integer) number of posterization bands. If
     * less than or equal to one, this is the rounding increment.
     * @return Color
     * @throws ColorSpaceException
     */
    public function posterize(float $quantum): Color
    {
        if ($quantum > 1) {
            $bands = round($quantum);
        } elseif ($quantum > 0) {
            $bands = round(1 / $quantum);
        } else {
            throw new ColorSpaceException($quantum . ' is an invalid posterization quantum.');
        }
        /** @var Color $color */
        $color = new (self::class)();
        if ($bands == 1) {
            // One band... everything is gray
            $color->setRed(0.5);
            $color->setGreen(0.5);
            $color->setBlue(0.5);
        } else {
            /*
             * The banding formula returns results greater than one; the color
             * constructor handles this edge case.
             */
            $color->setRed((floor($bands * $this->red)) / ($bands - 1));
            $color->setGreen((floor($bands * $this->green)) / ($bands - 1));
            $color->setBlue((floor($bands * $this->blue)) / ($bands - 1));
        }
        $color->setAlpha($this->alpha);
        return $color;
    }

    protected function register(array $spaces): void
    {
        foreach ($spaces as $space) {
            self::$spaceMap[$space] = self::class;
        }
    }

    /**
     * Convert a set of RGBA float values in the range 0..1 into an integer.
     * @param float $red
     * @param float $green
     * @param float $blue
     * @param float $alpha
     * @return int
     */
    public static function rgbaToInt(
        float $red = 0.0,
        float $green = 0.0,
        float $blue = 0.0,
        float $alpha = 1.0
    ): int
    {
        return (round(255 * $alpha) << 24) | (round(255 * $red) << 16)
            | (round(255 * $green) << 8) | round(255 * $blue);
    }

    /**
     * Add component values into a running sum.
     * @param array $sum The running sum.
     * @param float $weight How much weight to give to this color.
     */
    public function runningSum(array &$sum, float $weight = 1.0): self
    {
        $sum[0] += $weight * $this->red;
        $sum[1] += $weight * $this->green;
        $sum[2] += $weight * $this->blue;
        $sum[3] += $weight * $this->alpha;

        return $this;
    }

    /**
     * Set the alpha value.
     * @param float|string $alpha
     * @return $this
     * @throws ColorSpaceException
     */
    public function setAlpha(float|string $alpha): self
    {
        if (is_string($alpha)) {
            $alpha = strtolower($alpha);
        }
        if ($alpha !== 'none') {
            $this->alpha = $this->limit($alpha, true);
            $this->recompute = true;
        }

        return $this;
    }

    /**
     * Set the blue value.
     * @param float|int|string $blue
     * @return $this
     * @throws ColorSpaceException
     */
    public function setBlue(float|int|string $blue): self
    {
        if (is_string($blue)) {
            $blue = strtolower($blue);
        }
        if ($blue !== 'none') {
            $this->blue = $this->limit($blue);
            $this->recompute = true;
        }
        return $this;
    }

    /**
     * Set a gray level value.
     * @param float|int|string $grayLevel
     * @return $this
     * @throws ColorSpaceException
     */
    public function setGray(float|int|string $grayLevel): self
    {
        if (is_string($grayLevel)) {
            $grayLevel = strtolower($grayLevel);
        }
        if ($grayLevel !== 'none') {
            $this->red = $this->limit($grayLevel);
            $this->green = $this->red;
            $this->blue = $this->red;
            $this->recompute = true;
        }

        return $this;
    }

    /**
     * Set the green value.
     * @param float|int|string $green
     * @return $this
     * @throws ColorSpaceException
     */
    public function setGreen(float|int|string $green): self
    {
        if (is_string($green)) {
            $green = strtolower($green);
        }
        if ($green !== 'none') {
            $this->green = $this->limit($green);
            $this->recompute = true;
        }
        return $this;
    }

    /**
     * Set color based on a CSS style hexadecimal string.
     *
     * @param string $hex All non-hex characters are filtered from the string. The
     * resulting string must have 3 or 6 digits.
     * @return Color The current object.
     * @throws ColorSpaceException If the string is not valid.
     */
    public function setHex(string $hex): self
    {
        $clean = preg_replace('![^0-9a-f]!i', '', $hex);
        switch (strlen($clean)) {
            case 3:
                $r = hexdec($clean[0] . $clean[0]) / 255.0;
                $g = hexdec($clean[1] . $clean[1]) / 255.0;
                $b = hexdec($clean[2] . $clean[2]) / 255.0;
                break;

            case 6:
                $r = hexdec($clean[0] . $clean[1]) / 255.0;
                $g = hexdec($clean[2] . $clean[3]) / 255.0;
                $b = hexdec($clean[4] . $clean[5]) / 255.0;
                break;

            default:
                throw new ColorSpaceException('Unable to parse "' . $hex . '" as hex color.');

        }
        $this->setRgb($r, $g, $b);
        return $this;
    }

    /**
     * Set a color using a predefined name.
     * @param string $name
     * @param float|int|string $alpha
     * @return $this
     * @throws ColorSpaceException
     */
    public function setNamedColor(string $name, float|int|string $alpha = 0): self
    {
        $name = strtolower($name);
        if (!isset(self::$namedColors[$name])) {
            throw new ColorSpaceException("Unknown named color: $name");
        }
        $this->setRgbaInt(self::$namedColors[$name]);
        $this->setAlpha($alpha);

        return $this;
    }

    /**
     * Set the red value of a color.
     * @param float|int|string $red
     * @return $this
     * @throws ColorSpaceException
     */
    public function setRed(float|int|string $red): self
    {
        if (is_string($red)) {
            $red = strtolower($red);
        }
        if ($red !== 'none') {
            $this->red = $this->limit($red);
            $this->recompute = true;
        }

        return $this;
    }

    /**
     * Set color using RGB values, without affecting alpha.
     * @param float|int|array|string $red
     * @param float|int|string $green
     * @param float|int|string $blue
     * @return $this
     * @throws ColorSpaceException
     */
    public function setRgb(
        float|int|array|string $red,
        float|int|string $green = 0.0,
        float|int|string $blue = 0.0
    ): self
    {
        if (is_array($red)) {
            if (count($red) < 3) {
                throw new ColorSpaceException('Array must have 3 elements.');
            }
            [$red, $green, $blue] = $red;
        }
        $this->setBlue($blue);
        $this->setGreen($green);
        $this->setRed($red);

        return $this;
    }

    /**
     * Set RGB color components.
     *
     * @param array|int|float|string $red If an array, elements are converted to scalar
     * parameters. If scalar, red component.
     * @param int|float|string $green Green component.
     * @param int|float|string $blue Blue component.
     * @param float|string $alpha Alpha component.
     * @return Rgb The current object.
     * @throws ColorSpaceException
     */
    public function setRgba(
        float|int|array|string $red,
        float|int|string $green = 0.0,
        float|int|string $blue = 0.0,
        float|string $alpha = 1.0
    ): self
    {
        if (is_array($red)) {
            while (count($red) < 4) {
                $red[] = 0;
            }
            [$red, $green, $blue, $alpha] = $red;
        }
        $this->setAlpha($alpha);
        $this->setRgb($red, $green, $blue);

        return $this;
    }

    /**
     * Set red, green, blue and alpha values from an integer.
     * @param int $integerColor
     * @return $this
     * @throws ColorSpaceException
     */
    public function setRgbaInt(int $integerColor): self
    {
        $this->setRgba(
            ($integerColor >> 16) & 0x0FF,
            ($integerColor >> 8) & 0x0FF,
            $integerColor & 0x0FF,
            ($integerColor >> 24) & 0x0FF
        );

        return $this;
    }

    public function toCssHex(): string
    {
        return '#' . $this->hex($this->red) . $this->hex($this->green) . $this->hex($this->blue);
    }

}
