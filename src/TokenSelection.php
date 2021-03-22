<?php


namespace Okdewit\Mirror;

class TokenSelection
{
    public int $startIndex;
    public int $endIndex;
    public TokenCollection $collection;

    public function __construct($startIndex, $endIndex, $collection)
    {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
        $this->collection = $collection;
    }

    public function slicer(): array
    {
        return [$this->startIndex, $this->endIndex + 1];
    }
}
