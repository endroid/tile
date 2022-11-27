<?php

declare(strict_types=1);

namespace Endroid\Tests\Tile;

use Endroid\Tile\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    public function testCreateTile()
    {
        $tile = new Tile('Life is too short to be generating tiles', 300);
        $tile->create();

        $this->assertTrue(true);
    }
}
