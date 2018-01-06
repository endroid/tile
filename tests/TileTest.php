<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Tests\Tile;

use Endroid\Tile\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    public function testCreateTile()
    {
        $tile = new Tile();
        $tile->setText('Life is too short to be generating tiles');
        $tile->setSize(300);
        $tile->create();

        $this->assertTrue(true);
    }
}
