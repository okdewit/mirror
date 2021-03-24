<?php


namespace Okdewit\Mirror;


use Illuminate\Support\Collection;

class TokenSelectionCollection extends Collection
{
    public function merge($items): self
    {
        return parent::merge($items)
            ->sortBy(fn(TokenSelection $selection) => $selection->keys()->first())
            ->values();
    }
}
