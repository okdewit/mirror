<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Tests\Fixtures\C;
use PHPUnit\Framework\TestCase;

class MethodStructureTest extends TestCase
{
    public function test_it_has_no_methods()
    {
        $source = SourceFile::fromPath(__DIR__ . '/../Fixtures/A/A.txt');
        $this->assertEmpty($source->getMethods());
    }

    public function test_it_has_methods()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['__construct', 'A', 'B', 'C', 'D', 'E', 'F'], $source->getMethods()->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_has_public_methods()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['__construct', 'A', 'B'], $source->getMethods([T_PUBLIC])->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_has_private_and_protected_methods()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['C', 'D', 'E', 'F'], $source->getMethods([T_PRIVATE, T_PROTECTED])->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_has_static_methods()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['B'], $source->getMethods([T_PUBLIC], true)->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_has_non_static_methods()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['C'], $source->getMethods([T_PRIVATE], false)->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_has_magic()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['__construct'], $source->getMethods([T_PUBLIC], false, true)->map(fn($method) => $method->getName())->toArray());
    }

    public function test_it_doesnt_have_magic()
    {
        $source = SourceFile::fromClassname(C::class);
        $this->assertEquals(['A'], $source->getMethods([T_PUBLIC], false, false)->map(fn($method) => $method->getName())->toArray());
    }
}
