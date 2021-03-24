<?php


namespace Okdewit\Mirror;

use Illuminate\Support\Str;
use Okdewit\Mirror\Structures\ClassStructure;
use Okdewit\Mirror\Structures\MethodStructure;
use Okdewit\Mirror\Structures\MethodStructureCollection;
use ReflectionClass;
use ReflectionException;
use function React\Promise\reject;

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

    public function getClass(): ?ClassStructure
    {
        $classes = $this->getClasses();
        return $classes->count() ? $classes->first() : null;
    }

    public function getClasses(): TokenSelectionCollection
    {
        $selections = $this->select([T_CLASS, T_STRING]);
        return $selections->map(fn(TokenSelection $selection) => $selection->pipeInto(ClassStructure::class));
    }

    /**
     * @param array|null $visibility Can be an array of [T_PUBLIC, T_PROTECTED, T_PRIVATE] to filter based on visibility
     * @param bool|null $static If set, it explicitly includes or explicitly excludes static methods
     * @param bool|null $magic If set, it explicitly includes or explicitly excludes special methods
     * @return MethodStructureCollection
     */
    public function getMethods(array $visibility = [T_PUBLIC, T_PROTECTED, T_PRIVATE], ?bool $static = null, ?bool $magic = null): MethodStructureCollection
    {
        $methods = new MethodStructureCollection();

        if ($static === true || $static === null) {
            $methods = $methods->merge($this->select([$visibility, T_STATIC, T_FUNCTION, T_STRING]));
        }

        if ($static === false || $static === null) {
            $methods = $methods->merge($this->select([$visibility, T_FUNCTION, T_STRING]));
        }

        $methods = $methods->map(
            fn(TokenSelection $methods) => $methods->pipeInto(MethodStructure::class)
        );

        if ($magic === true) return $methods->filter(fn(MethodStructure $method) => $method->isMagic())->values();
        if ($magic === false) return $methods->reject(fn(MethodStructure $method) => $method->isMagic())->values();

        return $methods;
    }
}
