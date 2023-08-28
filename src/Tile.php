<?php

declare(strict_types=1);

namespace Endroid\Tile;

use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

final class Tile
{
    public function __construct(
        private readonly string $text = '',
        private readonly int $size = 400,
        private readonly Background $background = Background::B,
        private readonly Imagine $imagine = new Imagine()
    ) {
    }

    public function save(string $filename): void
    {
        $this->render($filename);
    }

    public function render(string $filename = null): void
    {
        $image = $this->create();

        if (null === $filename) {
            $image->show('png');
            exit;
        } else {
            $image->save($filename);
        }
    }

    public function get(string $format = 'png'): string
    {
        $image = $this->create();

        return $image->get($format);
    }

    public function create(): ImageInterface
    {
        $image = $this->imagine->open($this->background->getPath());
        $this->renderText($image);

        if ($this->size != $image->getSize()->getHeight()) {
            $box = new Box($this->size, $this->size);
            $image->resize($box);
        }

        return $image;
    }

    public function renderText(ImageInterface $image): void
    {
        $palette = new RGB();
        $color = $palette->color('#005', 100);
        $font = new Font(dirname(__FILE__).'/../assets/trebuchet_bi.ttf', 24, $color);

        $blocks = [];
        $parts = explode('__', $this->text);

        $wordWidths = [];
        foreach ($parts as $key => $part) {
            $blocks[$key] = (array) preg_split('#[^a-z0-9,:\'\.-]#i', trim(strval($part)));
            foreach ($blocks[$key] as $word) {
                $wordWidths[] = $font->box(strval($word))->getWidth();
            }
        }

        $lineHeight = 45;
        $maxRatio = 0;
        $result = [];
        $space = $font->box('-')->getWidth();
        $stepWidth = 25;
        for ($maxWidth = floor(max($wordWidths) / $stepWidth) * $stepWidth; $maxWidth <= 375; $maxWidth += $stepWidth) {
            $wordIndex = 0;
            $lines = [];
            $maxLineWidth = 0;
            foreach ($blocks as $block) {
                $lines[] = '';
                $lineWidth = 0;
                foreach ($block as $word) {
                    if ($lineWidth + $wordWidths[$wordIndex] > $maxWidth && '' != $lines[count($lines) - 1]) {
                        $lines[] = '';
                        $lineWidth = 0;
                    }
                    $lines[count($lines) - 1] .= strval($word).' ';
                    $lineWidth += $wordWidths[$wordIndex] + $space;
                    if ($lineWidth - $space > $maxLineWidth) {
                        $maxLineWidth = $lineWidth - $space;
                    }
                    ++$wordIndex;
                }
            }
            $min = min(count($lines) * $lineHeight, $maxLineWidth);
            $max = max(count($lines) * $lineHeight, $maxLineWidth);
            $ratio = $min / $max;
            if ($ratio > $maxRatio) {
                $result = $lines;
                $maxRatio = $ratio;
            }
        }

        $y = $image->getSize()->getHeight() / 2 - $lineHeight * (count($result) / 2) + 4;
        foreach ($result as $line) {
            $box = $font->box(trim($line));
            $image->draw()->text(trim($line), $font, new Point(intval($image->getSize()->getWidth() / 2 - $box->getWidth() / 2), intval($y)));
            $y += $lineHeight;
        }
    }
}
