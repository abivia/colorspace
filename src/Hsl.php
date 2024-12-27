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
            $this->lightness = $max;
            if ($max) {
                $this->saturation = ($max - $min) / $max;
            } else {
                $this->saturation = 0.0;
            }
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
        if ($this->saturation) {
            $h = $this->hue * 6;
            $sector = floor($h);
            $pos = $h - $sector;
            $c1 = $this->lightness * (1 - $this->saturation);
            $c2 = $this->lightness * (1 - ($this->saturation * $pos));
            $c3 = $this->lightness * (1 - ($this->saturation * (1 - $pos)));
            //echo 'sec=' . $sector . ' pos=' . $pos . ' c1=' . $c1 . ' c2=' . $c2 . ' c3=' . $c3 . '<br/>';
            switch ($sector) {
                case 0:
                    {
                        $this->red = $this->lightness;
                        $this->green = $c3;
                        $this->blue = $c1;
                    }
                    break;

                case 1:
                    {
                        $this->red = $c2;
                        $this->green = $this->lightness;
                        $this->blue = $c1;
                    }
                    break;

                case 2:
                    {
                        $this->red = $c1;
                        $this->green = $this->lightness;
                        $this->blue = $c3;
                    }
                    break;

                case 3:
                    {
                        $this->red = $c1;
                        $this->green = $c2;
                        $this->blue = $this->lightness;
                    }
                    break;

                case 4:
                    {
                        $this->red = $c3;
                        $this->green = $c1;
                        $this->blue = $this->lightness;
                    }
                    break;

                case 5:
                    {
                        $this->red = $this->lightness;
                        $this->green = $c1;
                        $this->blue = $c2;
                    }
                    break;

            }
        } else {
            $this->red = $this->lightness;
            $this->green = $this->lightness;
            $this->blue = $this->lightness;
        }
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

    public function toCss(bool $legacy = false): string
    {
        $this->calculateHsl();
        $hasAlpha = $this->alpha != 1.0;
        $delimit = $legacy ? ',' : ' ';
        $result = $hasAlpha ? 'hsla(' : 'hsl(';
        $result .= round($this->hue * 255)
            . $delimit . self::asPercent($this->saturation)
            . $delimit . self::asPercent($this->lightness);
        if ($hasAlpha) {
            $result .= ($legacy ? ',' : ' / ') . round($this->alpha, 4);
        }
        $result .= ')';

        return $result;
    }

}
