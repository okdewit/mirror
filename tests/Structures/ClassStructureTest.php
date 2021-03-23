<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Tests\Fixtures\C;
use PHPUnit\Framework\TestCase;

class ClassStructureTest extends TestCase
{
    public function test_it_has_class()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals('C', $source->getClass()->getName());
    }

    public function test_it_has_no_class()
    {
        $source = SourceFile::fromPath(__DIR__ . '/../Fixtures/A/A.txt');
        $this->assertNull($source->getClass());
    }

    public function test_it_has_classes()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['C', 'C2'], $source->getClasses()->map(fn($class) => $class->getName())->toArray());
    }
}
