<?php

declare(strict_types=1);

namespace Endroid\Tile\Gd;

final readonly class Color
{
    /**
     * @param int<0, 255> $red
     * @param int<0, 255> $green
     * @param int<0, 255> $blue
     * @param int<0, 127> $alpha
     */
    public function __construct(
        private int $red,
        private int $green,
        private int $blue,
        private int $alpha = 0,
    ) {
        /* @phpstan-ignore-next-line */
        if ($red < 0 || $red > 255) {
            throw new \InvalidArgumentException('Red must be between 0 and 255');
        }
        /* @phpstan-ignore-next-line */
        if ($green < 0 || $green > 255) {
            throw new \InvalidArgumentException('Green must be between 0 and 255');
        }
        /* @phpstan-ignore-next-line */
        if ($blue < 0 || $blue > 255) {
            throw new \InvalidArgumentException('Blue must be between 0 and 255');
        }
        /* @phpstan-ignore-next-line */
        if ($alpha < 0 || $alpha > 127) {
            throw new \InvalidArgumentException('Alpha must be between 0 and 127');
        }
    }

    /**
     * @return int<0, 255>
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @return int<0, 255>
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @return int<0, 255>
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * @return int<0, 127>
     */
    public function getAlpha(): int
    {
        return $this->alpha;
    }
}
