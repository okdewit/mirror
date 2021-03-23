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

        $this->assertEquals('declare(strict_types=1)', (string) $selection);
        $this->assertEquals('<?php declare( strict_types = 1 )', $selection->toRawString());
    }

    public function test_if_finds_a_complicated_pattern()
    {
        $source = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $selection = $source->selectFirst([T_PUBLIC, T_STATIC, T_FUNCTION, new Token(T_STRING, 'B'), '(', ')', '{']);
        $this->assertInstanceOf(TokenSelection::class, $selection);

        $selector = 'public static function B() {';

        $selection = $source->selectFirst(TokenCollection::fromString($selector)->toArray());
        $this->assertInstanceOf(TokenSelection::class, $selection);
        $this->assertEquals(60, $selection->startIndex);
        $this->assertEquals(70, $selection->endIndex);
        $this->assertEquals(TokenCollection::fromString($selector, true)->count(), $selection->count());
    }
}
