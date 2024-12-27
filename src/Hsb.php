<?php

namespace Abivia\ColorSpace;

use Abivia\ColorSpace\Color;

class Hsb extends Color
{
    /**
     * @var float Brightness, a component of the HSB model, ranges 0 to 1.
     */
    protected float $brightness = 0.0;
    /**
     * @var float Hue, a component of the HSB model, range 0 to 1.
     */
    protected float $hue = 0.0;
    /**
     * @var float Saturation, a component of the HSB model, ranges 0 to 1.
     */
    protected float $saturation = 0.0;

    /**
     * @param array|float|int|string|\Abivia\ColorSpace\Color $hue
     * @param float|int $saturation
     * @param float|int $value
     * @param float $alpha
     * @throws ColorSpaceException
     */
    public function __construct(
        array|float|int|string|Color $hue = 0.0,
        float|int $saturation = 0.0,
        float|int $value = 0.0,
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
            $this->setHsba($hue, $saturation, $value, $alpha);
        }
    }

    /**
     * Use the existing RGB values to calculate HSL
     *
     * @return void
     */
    protected function calculateHsb(): void
    {
        if ($this->recompute) {
            $max = (float)max($this->red, $this->green, $this->blue);
            $min = (float)min($this->red, $this->green, $this->blue);
            $this->brightness = $max;
            $this->saturation = $max ? ($max - $min) / $max : 0.0;
            $this->hue = parent::getHue();
            $this->recompute = false;
        }
    }

    /**
     * Use the existing HSB values to calculate RGB.
     *
     * @return void
     */
    protected function calculateRgb(): void
    {
        if ($this->saturation) {
            $h = $this->hue * 6;
            $sector = floor($h);
            $pos = $h - $sector;
            $c1 = $this->brightness * (1 - $this->saturation);
            $c2 = $this->brightness * (1 - ($this->saturation * $pos));
            $c3 = $this->brightness * (1 - ($this->saturation * (1 - $pos)));
            //echo 'sec=' . $sector . ' pos=' . $pos . ' c1=' . $c1 . ' c2=' . $c2 . ' c3=' . $c3 . '<br/>';
            switch ($sector) {
                case 0:
                    {
                        $this->red = $this->brightness;
                        $this->green = $c3;
                        $this->blue = $c1;
                    }
                    break;

                case 1:
                    {
                        $this->red = $c2;
                        $this->green = $this->brightness;
                        $this->blue = $c1;
                    }
                    break;

                case 2:
                    {
                        $this->red = $c1;
                        $this->green = $this->brightness;
                        $this->blue = $c3;
                    }
                    break;

                case 3:
                    {
                        $this->red = $c1;
                        $this->green = $c2;
                        $this->blue = $this->brightness;
                    }
                    break;

                case 4:
                    {
                        $this->red = $c3;
                        $this->green = $c1;
                        $this->blue = $this->brightness;
                    }
                    break;

                case 5:
                    {
                        $this->red = $this->brightness;
                        $this->green = $c1;
                        $this->blue = $c2;
                    }
                    break;

            }
        } else {
            $this->red = $this->brightness;
            $this->green = $this->brightness;
            $this->blue = $this->brightness;
        }
        $this->red = max(min($this->red, 1.0), 0.0);
        $this->green = max(min($this->green, 1.0), 0.0);
        $this->blue = max(min($this->blue, 1.0), 0.0);
    }

    /**
     * Get the color's brightness as a float
     * @return float
     */
    public function getBrightness(): float
    {
        $this->calculateHsb();
        return $this->brightness;
    }

    /**
     * Get the color's brightness as an int
     * @return int
     */
    public function getBrightnessInt(): int
    {
        $this->calculateHsb();
        return (int)round(255 * $this->brightness);
    }

    /**
     * Get the color's hue as a float
     * @return float
     */
    public function getHue(): float
    {
        $this->calculateHsb();
        return $this->hue;
    }

    /**
     * Get the color's saturation as a float
     * @return float
     */
    public function getSaturation(): float
    {
        $this->calculateHsb();
        return $this->saturation;
    }

    /**
     * Get the color's saturation as an int
     * @return int
     */
    public function getSaturationInt(): int
    {
        $this->calculateHsb();
        return (int)round(255 * $this->saturation);
    }

    /**
     * Set the "brightness" value.
     * @param float|int|string $brightness
     * @return $this
     * @throws ColorSpaceException
     */
    public function setBrightness(float|int|string $brightness): self
    {
        if (is_string($brightness)) {
            $brightness = strtolower($brightness);
        }
        if ($brightness !== 'none') {
            $this->brightness = $this->limit($brightness);
            $this->calculateRgb();
        }

        return $this;
    }

    /**
     * Set color using HSB values, without affecting alpha.
     * @param float|int|array $hue
     * @param float|int|string $saturation
     * @param float|int|string $brightness
     * @return $this
     * @throws ColorSpaceException
     */
    public function setHsb(
        float|int|array $hue,
        float|int|string $saturation = 0.0,
        float|int|string $brightness = 0.0
    ): self
    {
        if (is_array($hue)) {
            if (count($hue) < 3) {
                throw new ColorSpaceException('Array must have 3 elements.');
            }
            [$hue, $saturation, $brightness] = $hue;
        }
        $this->brightness = $this->limit($brightness);
        $this->hue = $this->limit($hue);
        $this->saturation = $this->limit($saturation);
        $this->calculateRgb();

        return $this;
    }

    /**
     * Set a colour using HSBA values.
     * @param float|int|array $hue
     * @param float|int|string $saturation
     * @param float|int|string $brightness
     * @param float|string $alpha
     * @return $this
     * @throws ColorSpaceException
     */
    public function setHsba(
        float|int|array $hue,
        float|int|string $saturation = 0.0,
        float|int|string $brightness = 0.0,
        float|string $alpha = 1.0
    ): self
    {
        if (is_array($hue)) {
            if (count($hue) < 4) {
                throw new ColorSpaceException('Array must have 4 elements.');
            }
            [$hue, $saturation, $brightness, $alpha] = $hue;
        }
        $this->alpha = $this->limit($alpha);
        $this->setHsb($hue, $saturation, $brightness);

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

    public function toString(int $precision = 2): string
    {
        $this->calculateHsb();
        return round($this->hue * 360, $precision)
            . ', ' . self::asPercent($this->saturation, $precision)
            . ', ' . self::asPercent($this->brightness, $precision);
    }

}
