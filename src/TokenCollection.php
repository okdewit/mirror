<?php


namespace Okdewit\Mirror;


use Illuminate\Support\Collection;

class TokenCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public static function fromString(string $string, bool $raw = false): self
    {
        $tokenCollection = new self(array_map(fn($baseToken) => Token::fromBasetoken($baseToken), token_get_all($string)));

        if (!$tokenCollection->first()->is(T_OPEN_TAG)) {
            $tokenCollection = self::fromString('<?php ' . $string, $raw)->skip(1);
        }

        return $raw ? $tokenCollection : $tokenCollection->reject(fn(Token $token) => $token->isIgnorable())->values();
    }

    public function locate(array $pattern): TokenLocationCollection
    {
        $matches = new TokenLocationCollection();

        foreach ($this->items as $sourceposition => $_) {
            $match = $this->findAt($pattern, $sourceposition);
            if (!$match) continue;

            $matches->push($match);
        }

        return $matches;
    }

    public function locateFirst(array $pattern): ?TokenLocation
    {
        foreach ($this->items as $sourceposition => $_) {
            $match = $this->findAt($pattern, $sourceposition);
            if ($match) return $match;
        }

        return null;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toRawString(): string
    {
        return $this
            ->map(fn(Token $token) => $token->text)
            ->implode('');
    }

    public function toString(): string
    {
        return $this
            ->filter(fn(Token $token) => !$token->isIgnorable())
            ->map(fn(Token $token) => $token->text)
            ->implode('');
    }

    protected function findAt(array $pattern, int $position): ?TokenLocation
    {
        $ignorableoffset = 0;

        // Try to match the full pattern on the current "cursor position"
        foreach ($pattern as $patternPosition => $patternFragment) {
            do {
                $index = $position + $patternPosition + $ignorableoffset;
                if ($this->keys()->last() < $index) return null;

                /** @var Token $token */
                $token = $this->items[$index];
                if ($token->is($patternFragment)) continue 2;
                if ($token->isIgnorable()) $ignorableoffset++;
            } while ($token->isIgnorable());

            return null;
        }

        return new TokenLocation($position, $index);
    }
}
