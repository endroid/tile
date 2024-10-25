<?php

declare(strict_types=1);

namespace Endroid\Tile\Twig\Extension;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TileExtension extends AbstractExtension
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tile_url', [$this, 'tileUrlFunction']),
        ];
    }

    public function tileUrlFunction(string $text, string $extension = 'png'): string
    {
        return $this->router->generate('tile', [
            'text' => $text,
            'extension' => $extension,
        ]);
    }
}
