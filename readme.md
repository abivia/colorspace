# Abivia ColorSpace - Color conversions and manipulation

This is a revisit of a library that was originally written for PHP 4, revised for PHP 5,
now completely rewritten for PHP 8.1+.

It allows conversions between colors in RGB, HSL, HSB (aka HSV), and CMYK spaces. 
This includes parsing RGB/HSL CSS colors in both "legacy" and "modern" syntax,
formatting as strings including CSS formats (hex, rgb, rgba, hsl, hsla). 
The library also supports a variety of color manipulation operations
(blend, difference, posterize, etc.)

```php
use Abivia\ColorSpace\Color;
use Abivia\ColorSpace\Hsl;
use Abivia\ColorSpace\Rgb;

// Legacy syntax. Returns an instance of Hsl.
$colorHsl = Color::fromCss('hsl(50%, 10%, 25%)');

// Modern syntax. Returns an instance of RGB
$colorRgb = Color::fromCss('rgba(50% 10% 25% / 0.5)');

// Convert RGB to HSL
$converted = new Hsl($colorRgb);

// Modern syntax
echo $converted->toCss();       // output: hsla(239 80% 50% / 0.5)

// Legacy Syntax
echo $converted->toCss(true);   // output: hsla(239,80%,50%,0.5)

// As hex (with no Alpha channel) 
echo $converted->toCssHex();    // output: #7f193f
```

The library also includes the full set of predefined colors in CSS, including "transparent".

```php
use Abivia\ColorSpace\Rgb;

$color = Color::fromCss('springgreen');
echo $color->toCssHex();        // output: #00ff7f

$color = Color::fromCss('transparent');
echo $color->toCss();           // output: rgba(0 0 0 / 0)
```
