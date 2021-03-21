<?php


namespace Okdewit\Mirror;


use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;

class SourceFile
{
    public string $path;
    public array $baseTokens;
    public TokenCollection $tokens;

    public function __construct($path)
    {
        $this->path = $path;
        $this->baseTokens = token_get_all(file_get_contents($path));
        $this->tokens = new TokenCollection(collect($this->baseTokens)->map(fn($token) => Token::fromBasetoken($token)));
    }

    /**
     * @param string|object $class
     * @return SourceFile
     * @throws ReflectionException
     */
    public static function fromClassname($class): self
    {
        return new self((new ReflectionClass($class))->getFileName());
    }

    public function locate(array $pattern): TokenLocationCollection
    {
        return $this->tokens->locate($pattern);
    }

    public function locateFirst(array $pattern): ?TokenLocation
    {
        return $this->tokens->locateFirst($pattern);
    }
}
