<?php

namespace Abivia\ColorSpace;

use Abivia\ColorSpace\Color;

class Hsl extends Color
{
    /**
     * @var float Hue, a component of the HSL model, range 0 to 1.
     */
    protected float $hue = 0.0;
    /**
     * @var float Lightness, a component of the HSL model, ranges 0 to 1.
     */
    protected float $lightness = 0.0;
    /**
     * @var float Saturation, a component of the HSL model, ranges 0 to 1.
     */
    protected float $saturation = 0.0;

    /**
     * @param array|float|int|string|\Abivia\ColorSpace\Color $hue
     * @param float|int $saturation
     * @param float|int $lightness
     * @param float $alpha
     * @throws ColorSpaceException
     */
    public function __construct(
        array|float|int|string|Color $hue = 0.0,
        float|int $saturation = 0.0,
        float|int $lightness = 0.0,
        float $alpha = 1.0
    )
    {
        /*
         * If this is the first time we're constructing an object, initialize
         * the hue delta factors.
         */
        if ($hue instanceof Color) {
            parent::__construct($hue);
        } else {
            parent::__construct();
            $this->setHsla($hue, $saturation, $lightness, $alpha);
        }
    }

    /**
     * Use the existing RGB values to calculate HSL
     *
     * @return void
     */
    protected function calculateHsl(): void
    {
        if ($this->recompute) {
            $max = (float)max($this->red, $this->green, $this->blue);
            $min = (float)min($this->red, $this->green, $this->blue);
            $this->lightness = ($min + $max) / 2.0;
            $sat = 1 - abs($min + $max - 1);
            $this->saturation = $sat ? ($max - $min) / $sat : 0.0;
            $this->hue = parent::getHue();
            $this->recompute = false;
        }
    }

    /**
     * Use the existing HSL values to calculate RGB.
     *
     * @return void
     */
    protected function calculateRgb(): void
    {
        $chroma = (1 - abs(2 * $this->lightness - 1)) * $this->saturation;
        $hue6 = $this->hue * 6;
        $sector = floor($hue6);
        $x = $chroma * (1 - abs(fmod($hue6, 2) - 1));
        $mid = $this->lightness - ($chroma / 2.0);
        switch ($sector) {
            case 0:
                $this->red = $chroma;
                $this->green = $x;
                $this->blue = 0.0;
            break;
            case 1:
                $this->red = $x;
                $this->green = $chroma;
                $this->blue = 0.0;
            break;
            case 2:
                $this->red = 0.0;
                $this->green = $chroma;
                $this->blue = $x;
                break;
            case 3:
                $this->red = 0.0;
                $this->green = $x;
                $this->blue = $chroma;
                break;
            case 4:
                $this->red = $x;
                $this->green = 0.0;
                $this->blue = $chroma;
                break;
            default:
                $this->red = $chroma;
                $this->green = 0.0;
                $this->blue = $x;
                break;
        }
        $this->red = max(min($this->red + $mid, 1.0), 0.0);
        $this->green = max(min($this->green + $mid, 1.0), 0.0);
        $this->blue = max(min($this->blue + $mid, 1.0), 0.0);
    }

    /**
     * Get the color's hue as a float
     * @return float
     */
    public function getHue(): float
    {
        $this->calculateHsl();
        return $this->hue;
    }

    /**
     * Get the color's lightness as a float
     * @return float
     */
    public function getLightness(): float
    {
        $this->calculateHsl();
        return $this->lightness;
    }

    /**
     * Get the color's lightness as an int
     * @return int
     */
    public function getLightnessInt(): int
    {
        $this->calculateHsl();
        return (int)round(255 * $this->lightness);
    }

    /**
     * Get the color's saturation as a float
     * @return float
     */
    public function getSaturation(): float
    {
        $this->calculateHsl();
        return $this->saturation;
    }

    /**
     * Get the color's saturation as an int
     * @return int
     */
    public function getSaturationInt(): int
    {
        $this->calculateHsl();
        return (int)round(255 * $this->saturation);
    }

    /**
     * Set color using HSL values, without affecting alpha.
     * @param float|int|array $hue
     * @param float|int|string $saturation
     * @param float|int|string $lightness
     * @return $this
     * @throws ColorSpaceException
     */
    public function setHsl(
        float|int|array $hue,
        float|int|string $saturation = 0.0,
        float|int|string $lightness = 0.0
    ): self
    {
        if (is_array($hue)) {
            if (count($hue) < 3) {
                throw new ColorSpaceException('Array must have 3 elements.');
            }
            [$hue, $saturation, $lightness] = $hue;
        }
        $this->lightness = $this->limit($lightness);
        $this->hue = $this->limit($hue);
        $this->saturation = $this->limit($saturation);
        $this->calculateRgb();

        return $this;
    }

    /**
     * Set a colour using HSLA values.
     * @param float|int|array $hue
     * @param float|int|string $saturation
     * @param float|int|string $lightness
     * @param float|string $alpha
     * @return $this
     * @throws ColorSpaceException
     */
    public function setHsla(
        float|int|array $hue,
        float|int|string $saturation = 0.0,
        float|int|string $lightness = 0.0,
        float|string $alpha = 1.0
    ): self
    {
        if (is_array($hue)) {
            if (count($hue) < 4) {
                throw new ColorSpaceException('Array must have 4 elements.');
            }
            [$hue, $saturation, $lightness, $alpha] = $hue;
        }
        $this->alpha = $this->limit($alpha);
        $this->setHsl($hue, $saturation, $lightness);

        return $this;
    }

    /**
     * Set the hue of a color.
     * @param float|int|string $hue
     * @return $this
     * @throws ColorSpaceException
     */
    public function setHue(float|int|string $hue): self
    {
        if (is_string($hue)) {
            $hue = strtolower($hue);
        }
        if ($hue !== 'none') {
            $this->hue = $this->limit($hue);
            $this->calculateRgb();
        }
        return $this;
    }

    public function setHueHuman(float $humanHue): self
    {
        $this->hue = self::humantoHue($humanHue);
        $this->calculateRgb();

        return $this;
    }

    /**
     * Set the lightness value.
     * @param float|int|string $lightness
     * @return $this
     * @throws ColorSpaceException
     */
    public function setLightness(float|int|string $lightness): self
    {
        if (is_string($lightness)) {
            $lightness = strtolower($lightness);
        }
        if ($lightness !== 'none') {
            $this->lightness = $this->limit($lightness);
            $this->calculateRgb();
        }

        return $this;
    }

    /**
     * Set the color's saturation value.
     * @param float|int|string $saturation
     * @return $this
     * @throws ColorSpaceException
     */
    public function setSaturation(float|int|string $saturation): self
    {
        if (is_string($saturation)) {
            $saturation = strtolower($saturation);
        }
        if ($saturation !== 'none') {
            $this->saturation = $this->limit($saturation);
            $this->calculateRgb();
        }

        return $this;
    }

    public function toCss(bool $legacy = false, int $precision = 2): string
    {
        $this->calculateHsl();
        $hasAlpha = $this->alpha != 1.0;
        $delimit = $legacy ? ', ' : ' ';
        $result = $hasAlpha ? 'hsla(' : 'hsl(';
        $result .= round($this->hue * 360, $precision)
            . $delimit . self::asPercent($this->saturation, $precision)
            . $delimit . self::asPercent($this->lightness, $precision);
        if ($hasAlpha) {
            $result .= ($legacy ? ', ' : ' / ') . round($this->alpha, $precision);
        }
        $result .= ')';

        return $result;
    }

    public function toString(int $precision = 2): string
    {
        return round($this->hue * 360, $precision)
            . ', ' . self::asPercent($this->saturation, $precision)
            . ', ' . self::asPercent($this->lightness, $precision);
    }

}
