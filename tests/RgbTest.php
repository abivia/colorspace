<?php

require_once '..\vendor\autoload.php';

use Abivia\ColorSpace\Color;
use Abivia\ColorSpace\ColorSpaceException;
use Abivia\ColorSpace\Rgb;
use PHPUnit\Framework\TestCase;

class RgbTest extends TestCase
{
    public function testAlpha()
    {
        $rgb = new Rgb();
        $this->assertEquals(1.0, $rgb->getAlpha());
        $rgb->setAlpha(0.5);
        $this->assertEquals(0.5, $rgb->getAlpha());
        $rgb->setAlpha('0.4');
        $this->assertEquals(0.4, $rgb->getAlpha());
        $rgb->setAlpha('35%');
        $this->assertEquals(0.35, $rgb->getAlpha());
        $rgb->setAlpha(-0.5);
        $this->assertEquals(0.0, $rgb->getAlpha());
        $rgb->setAlpha(7);
        $this->assertEquals(1.0, $rgb->getAlpha());
        $rgb->setAlpha(0.5);
        $rgb->setAlpha('none');
        $this->assertEquals(0.5, $rgb->getAlpha());
        $this->expectException(ColorSpaceException::class);
        $rgb->setAlpha('bad');
    }

    public function testBlue()
    {
        $rgb = new Rgb();
        $this->assertEquals(0.0, $rgb->getBlue());
        $this->assertEquals(0, $rgb->getBlueInt());
        $rgb->setBlue(0.5);
        $this->assertEquals(0.5, $rgb->getBlue());
        $this->assertEquals(128, $rgb->getBlueInt());
        $rgb->setBlue('32');
        $this->assertEquals(0.1254901961, round($rgb->getBlue(), 10));
        $this->assertEquals(32, $rgb->getBlueInt());
        $rgb->setBlue('35%');
        $this->assertEquals(0.35, $rgb->getBlue());
        $rgb->setBlue(-0.5);
        $this->assertEquals(0.0, $rgb->getBlue());
        $rgb->setBlue(700);
        $this->assertEquals(1.0, $rgb->getBlue());
        $this->assertEquals(255, $rgb->getBlueInt());
        $rgb->setBlue(0.5);
        $rgb->setBlue('none');
        $this->assertEquals(0.5, $rgb->getBlue());
        $this->expectException(ColorSpaceException::class);
        $rgb->setBlue('bad');
    }

    public function testFromCssHex()
    {
        $color = Color::fromCss('#202020');
        $this->assertInstanceOf(Rgb::class, $color);
        $this->assertEquals('rgb(32 32 32)', $color->toCss());
        $this->assertEquals('rgb(32, 32, 32)', $color->toCss(true));
        $this->assertEquals('#202020', $color->toCssHex());
        $color->setAlpha(0.2);
        $this->assertEquals('rgba(32 32 32 / 0.2)', $color->toCss());
        $this->assertEquals('rgba(32, 32, 32, 0.2)', $color->toCss(true));
    }

    public function testFromCssRgb()
    {
        $color = Color::fromCss('rgb(32 12.6% 32)');
        $this->assertInstanceOf(Rgb::class, $color);
        $this->assertEquals('rgb(32 32 32)', $color->toCss());
        $this->assertEquals('rgb(32, 32, 32)', $color->toCss(true));
        $this->assertEquals('#202020', $color->toCssHex());
        $color = Color::fromCss('rgb(32 12.6% 32 / 0.2)');
        $this->assertEquals('rgba(32 32 32 / 0.2)', $color->toCss());
        $this->assertEquals('rgba(32, 32, 32, 0.2)', $color->toCss(true));
    }

    public function testFromCssRgbLegacy()
    {
        $color = Color::fromCss('rgb(32, 32, 32)');
        $this->assertInstanceOf(Rgb::class, $color);
        $this->assertEquals('rgb(32 32 32)', $color->toCss());
        $this->assertEquals('rgb(32, 32, 32)', $color->toCss(true));
        $this->assertEquals('#202020', $color->toCssHex());
        $color = Color::fromCss('rgb(32,12.6%,32,0.2)');
        $this->assertEquals('rgba(32 32 32 / 0.2)', $color->toCss());
        $this->assertEquals('rgba(32, 32, 32, 0.2)', $color->toCss(true));
    }

    public function testGetHex()
    {
        $rgb = new Rgb(64, 31, 16, 0.505);
        $this->assertEquals('401f10', $rgb->getHex());
        $this->assertEquals('401f1080', $rgb->getHex(true));
    }

    public function testGetHue()
    {
        $rgb = new Rgb(64, 31, 16, 0.505);
        $this->assertEquals(0.0520833333, round($rgb->getHue(), 10));
    }

    public function testGray()
    {
        $rgb = new Rgb(40, 40, 40);
        $this->assertEquals(0.1568627451, round($rgb->getGray(), 10));
    }

    public function testGreen()
    {
        $rgb = new Rgb();
        $this->assertEquals(0.0, $rgb->getGreen());
        $this->assertEquals(0, $rgb->getGreenInt());
        $rgb->setGreen(0.5);
        $this->assertEquals(0.5, $rgb->getGreen());
        $this->assertEquals(128, $rgb->getGreenInt());
        $rgb->setGreen('32');
        $this->assertEquals(0.1254901961, round($rgb->getGreen(), 10));
        $this->assertEquals(32, $rgb->getGreenInt());
        $rgb->setGreen('35%');
        $this->assertEquals(0.35, $rgb->getGreen());
        $rgb->setGreen(-0.5);
        $this->assertEquals(0.0, $rgb->getGreen());
        $rgb->setGreen(700);
        $this->assertEquals(1.0, $rgb->getGreen());
        $this->assertEquals(255, $rgb->getGreenInt());
        $rgb->setGreen(0.5);
        $rgb->setGreen('none');
        $this->assertEquals(0.5, $rgb->getGreen());
        $this->expectException(ColorSpaceException::class);
        $rgb->setGreen('bad');
    }

    public function testNamedColors()
    {
        $color = Color::fromCss('springgreen');
        $this->assertEquals('#00ff7f', $color->toCssHex());
        $color = Color::fromCss('transparent');
        $this->assertEquals('rgba(0 0 0 / 0)', $color->toCss());
    }

    public function testRed()
    {
        $rgb = new Rgb();
        $this->assertEquals(0.0, $rgb->getRed());
        $this->assertEquals(0, $rgb->getRedInt());
        $rgb->setRed(0.5);
        $this->assertEquals(0.5, $rgb->getRed());
        $this->assertEquals(128, $rgb->getRedInt());
        $rgb->setRed('32');
        $this->assertEquals(0.1254901961, round($rgb->getRed(), 10));
        $this->assertEquals(32, $rgb->getRedInt());
        $rgb->setRed('35%');
        $this->assertEquals(0.35, $rgb->getRed());
        $rgb->setRed(-0.5);
        $this->assertEquals(0.0, $rgb->getRed());
        $rgb->setRed(700);
        $this->assertEquals(1.0, $rgb->getRed());
        $this->assertEquals(255, $rgb->getRedInt());
        $rgb->setRed(0.5);
        $rgb->setRed('none');
        $this->assertEquals(0.5, $rgb->getRed());
        $this->expectException(ColorSpaceException::class);
        $rgb->setRed('bad');
    }

    public function test__construct()
    {
        $obj = new Rgb(25, 25, 25);
        $this->assertEquals(0.09803921568627451, $obj->getRed());
        $this->assertEquals(0.09803921568627451, $obj->getGreen());
        $this->assertEquals(0.09803921568627451, $obj->getBlue());
        $this->assertEquals(1.0, $obj->getAlpha());
    }

}
