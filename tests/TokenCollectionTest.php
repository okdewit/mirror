<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Tests\Fixtures\C;
use Okdewit\Mirror\Token;
use Okdewit\Mirror\TokenCollection;
use Okdewit\Mirror\TokenLocation;
use PHPUnit\Framework\TestCase;

class TokenCollectionTest extends TestCase
{
    public function test_it_finds_a_pattern()
    {
        $source = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $location = $source->locateFirst([T_OPEN_TAG, T_DECLARE, '(', T_STRING, '=', T_LNUMBER, ')']);
        $this->assertInstanceOf(TokenLocation::class, $location);

        $tokens = $source->tokens->slice(...$location->slicer());

        $this->assertEquals('declare(strict_types=1)', (string) $tokens);
        $this->assertEquals('<?php declare( strict_types = 1 )', $tokens->toRawString());
    }

    public function test_if_finds_a_complicated_pattern()
    {
        $source = SourceFile::fromClassname(C::class);

        /** @var Token $token */
        $location = $source->locateFirst([T_PUBLIC, T_STATIC, T_FUNCTION, new Token(T_STRING, 'B'), '(', ')', '{']);
        $this->assertInstanceOf(TokenLocation::class, $location);


        dd(TokenCollection::fromString('public static function B() {')->toArray());
        $location = $source->locateFirst(TokenCollection::fromString('public static function B() {')->toArray());
        $this->assertInstanceOf(TokenLocation::class, $location);
    }
}
