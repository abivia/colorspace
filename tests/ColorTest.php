<?php

require_once '..\vendor\autoload.php';

use Abivia\ColorSpace\Color;
use Abivia\ColorSpace\Hsl;
use Abivia\ColorSpace\Rgb;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    public function testAsPercent()
    {
        $this->assertEquals('10%', Color::asPercent(0.1));
        $this->assertEquals('10.5%', Color::asPercent(0.105));
        $this->assertEquals('10.05%', Color::asPercent(0.1005));
        $this->assertEquals('10.01%', Color::asPercent(0.10005));
        $this->assertEquals('10.005%', Color::asPercent(0.10005, 3));
    }

    public function testFromCssHex()
    {
        $color = Color::fromCss('#202020');
        $this->assertInstanceOf(Rgb::class, $color);
    }

    public function testFromCssHsl()
    {
        $color = Color::fromCss('hsl(50%, 10%, 25%)');
        $this->assertInstanceOf(Hsl::class, $color);
    }

    public function testFromCssRgb()
    {
        $color = Color::fromCss('rgba(50% 10% 25% / 0.5)');
        $this->assertInstanceOf(Rgb::class, $color);

    }

    public function testLimit()
    {
        $this->assertEquals(0.0, Color::limit(0));
        $this->assertEquals(0.0, Color::limit(0.0));
        $this->assertEquals(0.0, Color::limit('0'));
        $this->assertEquals(0.0, Color::limit('0%'));
        $this->assertEquals(0.0, Color::limit(-1));
        $this->assertEquals(0.0, Color::limit(-0.5));
        $this->assertEquals(0.0, Color::limit('-10'));
        $this->assertEquals(0.0, Color::limit('-10%'));

        $this->assertEquals(1.0, Color::limit(255));
        $this->assertEquals(1.0, Color::limit(1.0));
        $this->assertEquals(0.00392156862745098, Color::limit('1'));
        $this->assertEquals(1.0, Color::limit('1', true));
        $this->assertEquals(1.0, Color::limit('100%'));
        $this->assertEquals(1.0, Color::limit(267));
        $this->assertEquals(1.0, Color::limit(4.0));
        $this->assertEquals(1.0, Color::limit('7', true));
        $this->assertEquals(1.0, Color::limit('150%'));

        $this->assertEquals(0.00392156862745098, Color::limit(1));
        $this->assertEquals(0.5, Color::limit(0.5));
        $this->assertEquals(0.6, Color::limit('0.6', true));
        $this->assertEquals(0.1, Color::limit('10%'));
    }

    public function testRgbaToInt()
    {
        $this->assertEquals(0xff000000, Color::rgbaToInt());
        $this->assertEquals(0, Color::rgbaToInt(0,0,0,0));
        $this->assertEquals(0x80808080, Color::rgbaToInt(0.5,0.5,0.5,0.5));
        $this->assertEquals(0xffffffff, Color::rgbaToInt(1,1,1,1));
    }

}
