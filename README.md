Endroid Tile
============

*By [endroid](http://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![Build Status](http://img.shields.io/travis/endroid/Tile.svg)](http://travis-ci.org/endroid/Tile)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![License](http://img.shields.io/packagist/l/endroid/tile.svg)](https://packagist.org/packages/endroid/tile)
[![PayPayl donate button](http://img.shields.io/badge/paypal-donate-orange.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGH86QN825TWN "Keep me off the streets")

Tile helps you generate images containing a typically Delft blue tile background and a saying.

```php
<?php

use Endroid\Tile\Tile;

$tile = new Tile();
$this->setBackground(Tile::BACKGROUND_C);
$tile->setText("Life is too short to be generating tiles");
$tile->setSize(300);
$tile->render();
```

![Tile](http://endroid.nl/tile/Life%20is%20too%20short%20to%20be%20generating%20tiles.png)

## Symfony

You can use [`EndroidTileBundle`](https://github.com/endroid/EndroidTileBundle) to enable this service in your Symfony application.

## Versioning

Semantic versioning ([semver](http://semver.org/)) is applied.

## License

This bundle is under the MIT license. For the full copyright and license information, please view the LICENSE file that
was distributed with this source code.
