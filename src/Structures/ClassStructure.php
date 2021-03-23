<?php declare(strict_types=1);

namespace Okdewit\Mirror\Structures;

use Okdewit\Mirror\Structures\Traits\HasBody;
use Okdewit\Mirror\Structures\Traits\HasName;
use Okdewit\Mirror\TokenSelection;

class ClassStructure extends TokenSelection
{
    use HasBody, HasName;
}
