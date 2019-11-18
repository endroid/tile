# Tile

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![Build Status](https://github.com/endroid/tile/workflows/CI/badge.svg)](https://github.com/endroid/tile/actions)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![License](http://img.shields.io/packagist/l/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)

This library helps you generate images containing a typically Delft blue tile with a saying.

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/tile
```

## Usage

```php
use Endroid\Tile\Tile;

$tile = new Tile();
$this->setBackground(Tile::BACKGROUND_C);
$tile->setText("Life is too short to be generating tiles");
$tile->setSize(300);
$tile->render();

```

![Tile](https://endroid.nl/tile/Life%20is%20too%20short%20to%20be%20generating%20tiles.png)

## Symfony integration

Register the Symfony bundle in the kernel.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\Tile\Bundle\TileBundle\EndroidTileBundle(),
    ];
}
```

Add the following section to your routing to be able to reach the tile controller.

``` yml
EndroidTileBundle:
    resource:   "@EndroidTileBundle/Controller/"
    type:       annotation
    prefix:     /tile
```

Now tiles can be generated by appending the tile text to the url as mounted, followed
by the file extension, like /tile/Life_is_too_short_to_be_generating_tiles.png.

## Twig extension

The bundle also provides a Twig extension for quickly generating tile urls.

``` twig
<img src="{{ tile_url(message) }}" />
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.