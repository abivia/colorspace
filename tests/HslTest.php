<?php

require_once '..\vendor\autoload.php';

use Abivia\ColorSpace\Color;
use Abivia\ColorSpace\Hsl;
use Abivia\ColorSpace\Rgb;
use PHPUnit\Framework\TestCase;

class HslTest extends TestCase
{
    public function test__construct()
    {
        $obj = new Hsl(25, 25, 25);
        $this->assertEquals(0.0980392157, round($obj->getHue(), 10));
        $this->assertEquals(0.0980392157, round($obj->getSaturation(), 10));
        $this->assertEquals(0.0980392157, round($obj->getLightness(), 10));
        $this->assertEquals(1.0, $obj->getAlpha());
    }

    public function testFromCssHsl()
    {
        $color = Color::fromCss('hsl(180, 10%, 25%)');
        $this->assertInstanceOf(Hsl::class, $color);
        $this->assertEquals('hsl(180 10% 25%)', $color->toCss());
        $this->assertEquals('hsl(180, 10%, 25%)', $color->toCss(true));
        $this->assertEquals('#394646', $color->toCssHex());
        $color->setAlpha(0.2);
        $this->assertEquals('hsla(180 10% 23.75% / 0.2)', $color->toCss());
        $this->assertEquals('hsla(180, 10%, 23.75%, 0.2)', $color->toCss(true));
    }

    public function testFromRgb()
    {
        $rgb = new Rgb(127, 127, 255);
        $hue = $rgb->getHue();
        $hsl = new Hsl($rgb);
        $this->assertEquals($hue, $hsl->getHue());
    }

}
