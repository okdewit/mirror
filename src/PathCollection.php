<?php


namespace Okdewit\Mirror;


use Illuminate\Support\LazyCollection;

class PathCollection extends LazyCollection
{
    public function __construct($source = null)
    {
        parent::__construct($source);
    }
}
