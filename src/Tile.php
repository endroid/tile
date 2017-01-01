<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Tile;

use Imagine\Gd\Font;
use Imagine\Gd\Image;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class Tile
{
    /**
     * @const string
     */
    const BACKGROUND_A = 'a';

    /**
     * @const string
     */
    const BACKGROUND_B = 'b';

    /**
     * @const string
     */
    const BACKGROUND_C = 'c';

    /**
     * @var string
     */
    protected $background = self::BACKGROUND_B;

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var int
     */
    protected $size = 400;

    /**
     * @var Image
     */
    protected $image = null;

    /**
     * Sets the background.
     *
     * @param $background
     *
     * @return Tile
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Returns the background.
     *
     * @return int
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Sets the text.
     *
     * @param $text
     *
     * @return Tile
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Returns the text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the size.
     *
     * @param $size
     *
     * @return Tile
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Returns the size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $filename
     */
    public function save($filename)
    {
        $this->render($filename);
    }

    /**
     * Renders the image to output or to the given filename.
     */
    public function render($filename = null)
    {
        $this->create();

        if ($filename === null) {
            $this->image->show('png');
            die;
        } else {
            $this->image->save($filename);
        }
    }

    /**
     * Returns the image resource in the given format.
     */
    public function get($format = 'png')
    {
        $this->create();

        return $this->image->get($format);
    }

    /**
     * Creates the image.
     */
    public function create()
    {
        $imagine = new Imagine();

        $this->image = $imagine->open(dirname(__FILE__).'/../assets/background_'.$this->background.'.png');
        $this->renderText();

        if ($this->size != $this->image->getSize()->getHeight()) {
            $box = new Box($this->size, $this->size);
            $this->image->resize($box);
        }
    }

    /**
     * Adds the text to the image.
     */
    public function renderText()
    {
        $palette = new RGB();
        $color = $palette->color('#005', 100);
        $font = new Font(dirname(__FILE__).'/../assets/trebuchet_bi.ttf', 24, $color);

        $blocks = explode('__', $this->text);

        $wordWidths = [];
        foreach ($blocks as $key => $block) {
            $blocks[$key] = preg_split('#[^a-z0-9,:\'\.-]#i', trim($block));
            foreach ($blocks[$key] as $word) {
                $wordWidths[] = $font->box($word)->getWidth();
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
                    if ($lineWidth + $wordWidths[$wordIndex] > $maxWidth && $lines[count($lines) - 1] != '') {
                        $lines[] = '';
                        $lineWidth = 0;
                    }
                    $lines[count($lines) - 1] .= $word.' ';
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
            $this->image->draw()->text(trim($line), $font, new Point($this->image->getSize()->getWidth() / 2 - $box->getWidth() / 2, $y));
            $y += $lineHeight;
        }
    }
}
