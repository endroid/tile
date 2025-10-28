<?php

declare(strict_types=1);

namespace Endroid\Tile\Gd;

final readonly class Font
{
    public function __construct(
        private string $file,
        private int $size,
        private Color $color,
    ) {
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function box(string $text, int $angle = 0): Box
    {
        $bbox = imagettfbbox($this->size, $angle, $this->file, $text);

        if (false === $bbox) {
            throw new \RuntimeException('Failed to calculate text bounding box');
        }

        $width = abs($bbox[4] - $bbox[0]);
        $height = abs($bbox[5] - $bbox[1]);

        return new Box($width, $height);
    }
}
