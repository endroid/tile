<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Tile\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Endroid\Tile\Tile;

/**
 * Tile controller.
 */
class TileController extends Controller
{
    /**
     * @Route("/{text}.{extension}", name="endroid_tile", requirements={"extension"="jpg|png|gif"})
     */
    public function generateAction($text, $extension)
    {
        if (strpos($text, ' ') !== false) {
            $text = str_replace(' ', '_', $text);

            return $this->redirect($this->generateUrl('endroid_tile', array(
                'text' => $text,
                'extension' => $extension,
            )));
        }

        $tile = new Tile();
        $tile->setText($text);
        $tile = $tile->get($extension);

        $mime_type = 'image/'.$extension;
        if ($extension == 'jpg') {
            $mime_type = 'image/jpeg';
        }

        return new Response($tile, 200, array('Content-Type' => $mime_type));
    }
}
