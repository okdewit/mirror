<?php


namespace Okdewit\Mirror;


class TokenLocation
{
    public int $startIndex;
    public int $endIndex;

    public function __construct($startIndex, $endIndex)
    {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
    }

    public function slicer(): array
    {
        return [$this->startIndex, $this->endIndex + 1];
    }
}
