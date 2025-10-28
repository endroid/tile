<?php

declare(strict_types=1);

namespace Endroid\Tile\Gd;

use GdImage as GdResource;

final class GdImage
{
    private GdResource $resource;

    public function __construct(GdResource $resource)
    {
        $this->resource = $resource;
    }

    public static function open(string $path): self
    {
        $resource = imagecreatefrompng($path);

        if (false === $resource) {
            throw new \RuntimeException(sprintf('Failed to load image from path: %s', $path));
        }

        imagealphablending($resource, true);
        imagesavealpha($resource, true);

        return new self($resource);
    }

    public function resize(Box $size): self
    {
        $resized = imagescale($this->resource, $size->getWidth(), $size->getHeight());

        if (false === $resized) {
            throw new \RuntimeException('Failed to resize image');
        }

        $this->resource = $resized;

        return $this;
    }

    public function getSize(): Box
    {
        return new Box(imagesx($this->resource), imagesy($this->resource));
    }

    public function text(string $text, Font $font, Point $position): self
    {
        $color = $font->getColor();
        $gdColor = imagecolorallocatealpha(
            $this->resource,
            $color->getRed(),
            $color->getGreen(),
            $color->getBlue(),
            $color->getAlpha()
        );

        if (false === $gdColor) {
            throw new \RuntimeException('Failed to allocate color');
        }

        imagettftext(
            $this->resource,
            $font->getSize(),
            0,
            $position->getX(),
            $position->getY(),
            $gdColor,
            $font->getFile(),
            $text
        );

        return $this;
    }

    public function get(string $format = 'png'): string
    {
        ob_start();

        match ($format) {
            'png' => imagepng($this->resource),
            'jpg', 'jpeg' => imagejpeg($this->resource),
            'gif' => imagegif($this->resource),
            default => throw new \InvalidArgumentException(sprintf('Unsupported format: %s', $format)),
        };

        return (string) ob_get_clean();
    }

    public function show(string $format = 'png'): void
    {
        header('Content-Type: image/'.$format);

        match ($format) {
            'png' => imagepng($this->resource),
            'jpg', 'jpeg' => imagejpeg($this->resource),
            'gif' => imagegif($this->resource),
            default => throw new \InvalidArgumentException(sprintf('Unsupported format: %s', $format)),
        };
    }

    public function save(string $path): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $result = match ($extension) {
            'png' => imagepng($this->resource, $path),
            'jpg', 'jpeg' => imagejpeg($this->resource, $path),
            'gif' => imagegif($this->resource, $path),
            default => throw new \InvalidArgumentException(sprintf('Unsupported file extension: %s', $extension)),
        };

        if (false === $result) {
            throw new \RuntimeException(sprintf('Failed to save image to: %s', $path));
        }
    }

    public function getResource(): GdResource
    {
        return $this->resource;
    }
}
