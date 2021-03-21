<?php declare(strict_types=1);

namespace Okdewit\Mirror;

use AppendIterator;
use Illuminate\Support\LazyCollection;
use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class SourceFileCollection extends LazyCollection
{
    /**
     * @param string|array|PathCollection $paths
     * @param string $baseRegex
     * @return static
     */
    public static function fromPath($paths, string $baseRegex = '/^.+\.php$/'): self
    {
        // Turn each path into a partial iterator,
        // and append all iterators together.
        $iterator = new AppendIterator();
        (new PathCollection(is_string($paths) ? [$paths] : $paths))
            ->map(fn($path) => self::iteratorFromPath($path, $baseRegex))
            ->each(fn($part) => $iterator->append($part));

        return (new self(fn() => yield from $iterator))
            ->keys()
            ->map(fn($path) => new SourceFile($path));
    }

    private static function iteratorFromPath(string $path, string $regex): Iterator
    {
        return new RegexIterator(
            (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path))),
            $regex,
            RecursiveRegexIterator::GET_MATCH
        );
    }
}
