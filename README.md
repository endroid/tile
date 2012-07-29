Endroid Tile
============

[![Build Status](https://secure.travis-ci.org/endroid/Tile.png)](http://travis-ci.org/endroid/Tile)

Tile helps you generate images containing a typically Delft blue tile background and a saying.

```php
<?php

$tile = new Endroid\Tile\Tile();
$tile->setText("Life is too short to be generating tiles");
$tile->setSize(300);
$tile->render();
```