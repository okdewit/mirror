<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\Tests\Fixtures\A\A;
use Okdewit\Mirror\Tests\Fixtures\C;
use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Token;
use PHPUnit\Framework\TestCase;


class SourceFileTest extends TestCase
{
    public function test_it_loads_files()
    {
        $A = SourceFile::fromClassname(A::class);
        $C = SourceFile::fromClassname(C::class);
        $this->assertEquals('A.php', basename($A->path));
        $this->assertEquals('C.php', basename($C->path));
    }

    public function test_it_tokenizes()
    {
        $C = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $token = $C->first();
        $this->assertTrue($token->is(T_OPEN_TAG));
    }
}
