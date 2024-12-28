<?php

namespace Abivia\ColorSpace;

use Abivia\ColorSpace\Color;

class Cmyk extends Color
{
    /**
     * @var float Black, a component of the CMYK model, ranges 0 to 1.
     */
    protected float $black = 0.0;
    /**
     * @var float Cyan, a component of the CMYK model, range 0 to 1.
     */
    protected float $cyan = 0.0;
    /**
     * @var float Magenta, a component of the CMYK model, ranges 0 to 1.
     */
    protected float $magenta = 0.0;
    /**
     * @var float Yellow, a component of the CMYK model, ranges 0 to 1.
     */
    protected float $yellow = 0.0;

    /**
     * @param array|float|int|string|\Abivia\ColorSpace\Color $cyan
     * @param float|int $magenta
     * @param float|int $yellow
     * @param float|int $black
     * @param float $alpha
     * @throws ColorSpaceException
     */
    public function __construct(
        array|float|int|string|Color $cyan = 0.0,
        float|int $magenta = 0.0,
        float|int $yellow = 0.0,
        float|int $black = 0.0,
        float $alpha = 1.0
    )
    {
        /*
         * If this is the first time we're constructing an object, initialize
         * the cyan delta factors.
         */
        if ($cyan instanceof Color) {
            parent::__construct($cyan);
        } else {
            parent::__construct();
            $this->setCmyka($cyan, $magenta, $yellow, $black, $alpha);
        }
    }

    /**
     * Use the existing RGB values to calculate CMYK
     *
     * @return void
     */
    protected function calculateCmyk(): void
    {
        if ($this->recompute) {
            $this->black = 1 - max($this->red, $this->green, $this->blue);
            if ($this->black === 1.0) {
                $this->cyan = 0.0;
                $this->magenta = 0.0;
                $this->yellow = 0.0;
            } else {
                $div = 1.0 - $this->black;
                $this->cyan = (1 - $this->red - $this->black) / $div;
                $this->magenta = (1 - $this->green - $this->black) / $div;
                $this->yellow = (1 - $this->blue - $this->black) / $div;
            }
            $this->recompute = false;
        }
    }

    /**
     * Use the existing CMYK values to calculate RGB.
     *
     * @return void
     */
    protected function calculateRgb(): void
    {
        $this->red = 1 - min(1, $this->cyan * (1 - $this->black) + $this->black);
        $this->green = 1 - min(1, $this->magenta * (1 - $this->black) + $this->black);
        $this->blue = 1 - min(1, $this->yellow * (1 - $this->black) + $this->black);
    }

    /**
     * Get the color's black as a float
     * @return float
     */
    public function getBlack(): float
    {
        $this->calculateCmyk();
        return $this->black;
    }

    /**
     * Get the color's black as a percentage
     * @param int $precision
     * @param string $symbol
     * @return string
     */
    public function getBlackPercent(int $precision = 2, string $symbol = '%'): string
    {
        $this->calculateCmyk();
        return round(100 * $this->black, $precision) . $symbol;
    }

    /**
     * Get the color's cyan as a float
     * @return float
     */
    public function getCyan(): float
    {
        $this->calculateCmyk();
        return $this->cyan;
    }

    /**
     * Get the color's cyan as a percentage
     * @param int $precision
     * @param string $symbol
     * @return string
     */
    public function getCyanPercent(int $precision = 2, string $symbol = '%'): string
    {
        $this->calculateCmyk();
        return round(100 * $this->cyan, $precision) . $symbol;
    }

    /**
     * Get the color's magenta as a float
     * @return float
     */
    public function getMagenta(): float
    {
        $this->calculateCmyk();
        return $this->magenta;
    }

    /**
     * Get the color's magenta as a percentage
     * @param int $precision
     * @param string $symbol
     * @return string
     */
    public function getMagentaPercent(int $precision = 2, string $symbol = '%'): string
    {
        $this->calculateCmyk();
        return round(100 * $this->magenta, $precision) . $symbol;
    }

    /**
     * Get the color's yellow as a float
     * @return float
     */
    public function getYellow(): float
    {
        $this->calculateCmyk();
        return $this->yellow;
    }

    /**
     * Get the color's yellow as a percentage
     * @param int $precision
     * @param string $symbol
     * @return string
     */
    public function getYellowPercent(int $precision = 2, string $symbol = '%'): string
    {
        $this->calculateCmyk();
        return round(100 * $this->yellow, $precision) . $symbol;
    }

    /**
     * Set the black value.
     * @param float|int|string $black
     * @return $this
     * @throws ColorSpaceException
     */
    public function setBlack(float|int|string $black): self
    {
        if (is_string($black)) {
            $black = strtolower($black);
        }
        if ($black !== 'none') {
            $this->black = $this->limit($black, 1.0);
            $this->calculateRgb();
        }

        return $this;
    }

    /**
     * Set color using CMYK values, without affecting alpha.
     * @param float|int|array $cyan
     * @param float|int|string $magenta
     * @param float|int|string $yellow
     * @param float|int|string $black
     * @return $this
     * @throws ColorSpaceException
     */
    public function setCmyk(
        float|int|array $cyan = 0.0,
        float|int|string $magenta = 0.0,
        float|int|string $yellow = 0.0,
        float|int|string $black = 0.0
    ): self
    {
        if (is_array($cyan)) {
            if (count($cyan) < 4) {
                throw new ColorSpaceException('Array must have 4 elements.');
            }
            [$cyan, $magenta, $yellow, $black] = $cyan;
        }
        $this->black = $this->limit($black, 1.0);
        $this->cyan = $this->limit($cyan, 1.0);
        $this->magenta = $this->limit($magenta, 1.0);
        $this->yellow = $this->limit($yellow, 1.0);
        $this->calculateRgb();

        return $this;
    }

    /**
     * Set a colour using CMYKA values.
     * @param float|int|array $cyan
     * @param float|int|string $magenta
     * @param float|int|string $black
     * @param float|string $alpha
     * @return $this
     * @throws ColorSpaceException
     */
    public function setCmyka(
        float|int|array $cyan = 0.0,
        float|int|string $magenta = 0.0,
        float|int|string $yellow = 0.0,
        float|int|string $black = 0.0,
        float|string $alpha = 1.0
    ): self
    {
        if (is_array($cyan)) {
            if (count($cyan) < 5) {
                throw new ColorSpaceException('Array must have 5 elements.');
            }
            [$cyan, $magenta, $yellow, $black, $alpha] = $cyan;
        }
        $this->alpha = $this->limit($alpha, 1.0);
        $this->setCmyk($cyan, $magenta, $yellow, $black);

        return $this;
    }

    /**
     * Set the cyan of a color.
     * @param float|int|string $cyan
     * @return $this
     * @throws ColorSpaceException
     */
    public function setCyan(float|int|string $cyan): self
    {
        if (is_string($cyan)) {
            $cyan = strtolower($cyan);
        }
        if ($cyan !== 'none') {
            $this->cyan = $this->limit($cyan, 1.0);
            $this->calculateRgb();
        }
        return $this;
    }

    /**
     * Set the color's magenta value.
     * @param float|int|string $magenta
     * @return $this
     * @throws ColorSpaceException
     */
    public function setMagenta(float|int|string $magenta): self
    {
        if (is_string($magenta)) {
            $magenta = strtolower($magenta);
        }
        if ($magenta !== 'none') {
            $this->magenta = $this->limit($magenta, 1.0);
            $this->calculateRgb();
        }

        return $this;
    }

    /**
     * Set the color's yellow value.
     * @param float|int|string $yellow
     * @return $this
     * @throws ColorSpaceException
     */
    public function setYellow(float|int|string $yellow): self
    {
        if (is_string($yellow)) {
            $yellow = strtolower($yellow);
        }
        if ($yellow !== 'none') {
            $this->yellow = $this->limit($yellow, 1.0);
            $this->calculateRgb();
        }

        return $this;
    }

    public function toString(int $precision = 2): string
    {
        $this->calculateCmyk();
        $add = ($this->alpha === 1.0) ? '' : ' / ' . self::asPercent($this->alpha, $precision);
        return self::asPercent($this->cyan, $precision)
            . ', ' . self::asPercent($this->magenta, $precision)
            . ', ' . self::asPercent($this->yellow, $precision)
            . ', ' . self::asPercent($this->black, $precision)
            . $add;
    }

}
