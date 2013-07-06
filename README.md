Endroid Tile
============

*By [endroid](http://endroid.nl/)*

[![Build Status](https://secure.travis-ci.org/endroid/Tile.png)](http://travis-ci.org/endroid/Tile)
[![Latest Stable Version](https://poser.pugx.org/endroid/tile/v/stable.png)](https://packagist.org/packages/endroid/tile)
[![Total Downloads](https://poser.pugx.org/endroid/tile/downloads.png)](https://packagist.org/packages/endroid/tile)

Tile helps you generate images containing a typically Delft blue tile background and a saying.

```php
<?php

$tile = new Endroid\Tile\Tile();
$tile->setText("Life is too short to be generating tiles");
$tile->setSize(300);
$tile->render();
```

![Tile](http://endroid.nl/tile/Life_is_too_short_to_be_generating_tiles.png)

## License

This bundle is under the MIT license. For the full copyright and license information, please view the LICENSE file that
was distributed with this source code.
