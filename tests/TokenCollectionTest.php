<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Tests\Fixtures\C;
use Okdewit\Mirror\Token;
use Okdewit\Mirror\TokenCollection;
use Okdewit\Mirror\TokenSelection;
use PHPUnit\Framework\TestCase;

class TokenCollectionTest extends TestCase
{
    public function test_it_finds_a_pattern()
    {
        $source = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $selection = $source->selectFirst([T_OPEN_TAG, T_DECLARE, '(', T_STRING, '=', T_LNUMBER, ')']);
        $this->assertInstanceOf(TokenSelection::class, $selection);

        $tokens = $source->tokens->slice(...$selection->slicer());

        $this->assertEquals('declare(strict_types=1)', (string) $tokens);
        $this->assertEquals('<?php declare( strict_types = 1 )', $tokens->toRawString());
    }

    public function test_if_finds_a_complicated_pattern()
    {
        $source = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $selection = $source->selectFirst([T_PUBLIC, T_STATIC, T_FUNCTION, new Token(T_STRING, 'B'), '(', ')', '{']);
        $this->assertInstanceOf(TokenSelection::class, $selection);

        $selection = $source->selectFirst(TokenCollection::fromString('public static function B() {')->toArray());
        $this->assertInstanceOf(TokenSelection::class, $selection);
    }
}
