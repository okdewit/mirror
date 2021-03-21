<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\PathCollection;
use Okdewit\Mirror\SourceFileCollection;
use PHPUnit\Framework\TestCase;

class SourceFileCollectionTest extends TestCase
{
    protected const PATH = __DIR__ . '/Fixtures';

    public function test_collect_files()
    {
        $this->assertCount(3, SourceFileCollection::fromPath(self::PATH));
        $this->assertCount(3, SourceFileCollection::fromPath(new PathCollection([self::PATH])));
        $this->assertCount(2, SourceFileCollection::fromPath([self::PATH. '/A', self::PATH . '/B']));
    }

    public function test_use_custom_regex()
    {
        $this->assertCount(1, SourceFileCollection::fromPath(self::PATH, '/^.+\.txt$/'));
    }
}
