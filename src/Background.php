<?php

declare(strict_types=1);

namespace Endroid\Tile;

enum Background: string
{
    case A = 'a';
    case B = 'b';
    case C = 'c';

    public function getPath(): string
    {
        return __DIR__.'/../assets/background_'.$this->value.'.png';
    }
}
