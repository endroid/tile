Endroid Tile
============

[![Build Status](https://secure.travis-ci.org/endroid/tile.png)](http://travis-ci.org/endroid/tile)

Tile helps you generate images containing a typically Delft blue tile background and a saying.

```php
<?php

$tile = new Endroid\Tile\Tile();
$tile->setText("Life is too short to be generating tiles");
$tile->render();
```