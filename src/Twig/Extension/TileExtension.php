<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Tile\Twig\Extension;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TileExtension extends AbstractExtension
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('tile_url', [$this, 'tileUrlFunction']),
        ];
    }

    public function tileUrlFunction(string $text, string $extension = 'png'): string
    {
        $url = $this->router->generate('tile', [
            'text' => $text,
            'extension' => $extension,
        ]);

        return $url;
    }
}
