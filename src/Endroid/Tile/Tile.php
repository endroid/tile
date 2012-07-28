<?php

namespace Endroid\Tile;

use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;

class Tile
{
    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var int
     */
    protected $size = 414;

    /**
     * @var null
     */
    protected $image = null;

    /**
     * @param $text
     * @return Tile
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param $size
     * @return Tile
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param $filename
     */
    public function save($filename)
    {
        $this->render($filename);
    }

    /**
     *
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
     *
     */
    public function create()
    {
        $imagine = new Imagine();

        $this->image = $imagine->open(dirname(__FILE__).'/../../../assets/background.jpg');
        $this->renderText();

        if ($this->size != $this->image->getSize()->getHeight()) {
            $box = new Box($this->size, $this->size);
            $this->image->resize($box);
        }
    }

    public function renderText()
    {
        $font = new Font(dirname(__FILE__).'/../../../assets/trebuchet_bi.ttf', 24, new Color('005'));

        $blocks = explode('__', $this->text);

        $wordWidths = array();
        foreach ($blocks as $key => $block) {
            $blocks[$key] = preg_split('#[^a-z0-9,\.-]#i', trim($block));
            foreach ($blocks[$key] as $word) {
                $wordWidths[] = $font->box($word)->getWidth();
            }
        }

        $lineHeight = 40;
        $maxRatio = 0;
        $result = array();
        $space = $font->box(' ')->getWidth();
        $stepWidth = 25;
        for ($maxWidth = floor(max($wordWidths) / $stepWidth) * $stepWidth; $maxWidth <= 375; $maxWidth += $stepWidth) {
            $wordIndex = 0;
            $lines = array();
            $maxLineWidth = 0;
            foreach ($blocks as $block) {
                $lines[] = '';
                $lineWidth = 0;
                foreach ($block as $word) {
                    if ($lineWidth + $wordWidths[$wordIndex] > $maxWidth) {
                        $lines[] = '';
                        $lineWidth = 0;
                    }
                    $lines[count($lines) - 1] .= $word.' ';
                    $lineWidth += $wordWidths[$wordIndex] + $space;
                    if ($lineWidth - $space > $maxLineWidth) {
                        $maxLineWidth = $lineWidth - $space;
                    }
                    $wordIndex++;
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

        $y = $this->image->getSize()->getHeight() / 2 - $lineHeight * (count($result) / 2);
        foreach ($result as $line) {
            $box = $font->box(trim($line));
            $this->image->draw()->text($line, $font, new Point($this->image->getSize()->getWidth() / 2 - $box->getWidth() / 2, $y));
            $y += $lineHeight;
        }
    }
}