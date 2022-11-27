<?php

declare(strict_types=1);

namespace Endroid\Tile;

use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class Tile
{
    public const BACKGROUND_A = 'a';
    public const BACKGROUND_B = 'b';
    public const BACKGROUND_C = 'c';

    private ImageInterface $image;

    public function __construct(
        private string $text = '',
        private int $size = 400,
        private string $background = self::BACKGROUND_B
    ) {
    }

    public function save(string $filename): void
    {
        $this->render($filename);
    }

    public function render(string $filename = null): void
    {
        $this->create();

        if (null === $filename) {
            $this->image->show('png');
            exit;
        } else {
            $this->image->save($filename);
        }
    }

    public function get(string $format = 'png'): string
    {
        $this->create();

        return $this->image->get($format);
    }

    public function create(): void
    {
        $imagine = new Imagine();

        $this->image = $imagine->open(__DIR__.'/../assets/background_'.$this->background.'.png');
        $this->renderText();

        if ($this->size != $this->image->getSize()->getHeight()) {
            $box = new Box($this->size, $this->size);
            $this->image->resize($box);
        }
    }

    public function renderText(): void
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

        $y = $this->image->getSize()->getHeight() / 2 - $lineHeight * (count($result) / 2) + 4;
        foreach ($result as $line) {
            $box = $font->box(trim($line));
            $this->image->draw()->text(trim($line), $font, new Point(intval($this->image->getSize()->getWidth() / 2 - $box->getWidth() / 2), intval($y)));
            $y += $lineHeight;
        }
    }
}
