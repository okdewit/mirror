<?php


namespace Okdewit\Mirror;

class TokenSelection
{
    public int $startIndex;
    public int $endIndex;
    public TokenCollection $selection;
    public TokenCollection $collection;

    public function __construct($startIndex, $endIndex, $collection)
    {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
        $this->collection = $collection;
        $this->selection = $collection->slice(...$this->slicer());
    }

    public function slicer(): array
    {
        return [$this->startIndex, $this->endIndex + 1];
    }
}
