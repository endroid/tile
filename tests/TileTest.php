<?php

declare(strict_types=1);

namespace Endroid\Tests\Tile;

use Endroid\Tile\Tile;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class TileTest extends TestCase
{
    #[TestDox('Check if a tile can be created')]
    public function testCreateTile(): void
    {
        $tile = new Tile('Life is too short to be generating tiles', 300);
        $tile->create();

        $this->assertInstanceOf(Tile::class, $tile);
    }
}
