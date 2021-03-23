<?php


namespace Okdewit\Mirror;

class TokenSelection extends TokenCollection
{
    public int $startIndex;
    public int $endIndex;
    public TokenCollection $collection;

    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public static function create($startIndex, $endIndex, TokenCollection $collection)
    {
        $selection = new self($collection->slice($startIndex, $endIndex-$startIndex+1));
        $selection->startIndex = $startIndex;
        $selection->endIndex = $endIndex;
        $selection->collection = $collection;

        return $selection;
    }
}
