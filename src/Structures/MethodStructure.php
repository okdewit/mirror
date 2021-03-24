<?php declare(strict_types=1);

namespace Okdewit\Mirror\Structures;

use Illuminate\Support\Str;
use Okdewit\Mirror\Structures\Traits\HasBody;
use Okdewit\Mirror\Structures\Traits\HasName;
use Okdewit\Mirror\TokenSelection;

class MethodStructure extends TokenSelection
{
    use HasBody, HasName;

    public function isMagic(): bool
    {
        return Str::startsWith($this->getName(), '__');
    }
}
