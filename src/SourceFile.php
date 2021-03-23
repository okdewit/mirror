<?php


namespace Okdewit\Mirror;


use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;

class SourceFile extends TokenCollection
{
    public string $path;
    public array $baseTokens;
    public TokenCollection $tokens;

    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * @param string|object $class
     * @return SourceFile
     * @throws ReflectionException
     */
    public static function fromClassname($class): self
    {
        return self::fromPath((new ReflectionClass($class))->getFileName());
    }

    /**
     * @param $path
     * @return SourceFile
     */
    public static function fromPath($path): self
    {
        $baseTokens = token_get_all(file_get_contents($path));

        $sourceFile = new self(collect($baseTokens)->map(fn($token) => Token::fromBasetoken($token)));
        $sourceFile->path = $path;
        $sourceFile->baseTokens = token_get_all(file_get_contents($path));

        return $sourceFile;
    }
}
