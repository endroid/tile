<?php

declare(strict_types=1);

namespace Endroid\Tile;

use Endroid\Tile\Gd\Box;
use Endroid\Tile\Gd\Color;
use Endroid\Tile\Gd\Font;
use Endroid\Tile\Gd\GdImage;
use Endroid\Tile\Gd\Point;

final readonly class Tile
{
    public function __construct(
        private string $text = '',
        private int $size = 400,
        private Background $background = Background::B,
    ) {
    }

    public function save(string $filename): void
    {
        $this->render($filename);
    }

    public function render(?string $filename = null): void
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

    public function create(): GdImage
    {
        $image = GdImage::open($this->background->getPath());
        $this->renderText($image);

        if ($this->size !== $image->getSize()->getHeight()) {
            $box = new Box($this->size, $this->size);
            $image->resize($box);
        }

        return $image;
    }

    public function renderText(GdImage $image): void
    {
        $color = new Color(0, 0, 85, 0);
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

        $fontSize = 24;
        $lineHeight = intval($fontSize * 1.8);
        $maxRatio = 0;
        $result = [];
        $space = $font->box('-')->getWidth();
        $stepWidth = 25;

        if ([] === $wordWidths) {
            return;
        }

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

        $totalTextHeight = count($result) * $lineHeight;
        $startY = ($image->getSize()->getHeight() - $totalTextHeight) / 2;

        foreach ($result as $index => $line) {
            $box = $font->box(trim($line));
            $x = intval($image->getSize()->getWidth() / 2 - $box->getWidth() / 2);
            $yBaseline = intval($startY + ($index * $lineHeight) + $fontSize);
            $image->text(trim($line), $font, new Point($x, $yBaseline));
        }
    }
}
