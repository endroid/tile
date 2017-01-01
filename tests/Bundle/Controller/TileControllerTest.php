<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TileControllerTest extends WebTestCase
{
    /**
     * Tests if the tile generation route returns success response.
     */
    public function testCreateTile()
    {
        $client = static::createClient();
        $client->request('GET', '/tile/Life_is_too_short_to_be_generating_tiles.png');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
