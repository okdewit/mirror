<?php declare(strict_types=1);

namespace Okdewit\Mirror\Structures\Traits;

use Okdewit\Mirror\Token;

trait HasName
{
    public function getName(): string
    {
        /** @var Token $token */
        $token = $this->selectFirst([T_STRING])->first();
        return $token->text;
    }
}
