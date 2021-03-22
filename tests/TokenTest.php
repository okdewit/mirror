<?php declare(strict_types=1);

namespace Okdewit\Mirror\Tests;

use Okdewit\Mirror\SourceFile;
use Okdewit\Mirror\Tests\Fixtures\C;
use Okdewit\Mirror\Token;
use Okdewit\Mirror\TokenSelection;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function test_it_is()
    {
        $token1a = new Token(T_STRING, 'foo');
        $token1b = new Token(T_STRING, 'foo');
        $token2 = new Token(T_STRING, 'bar');
        $token3 = new Token(0, '=');
        $token4 = new Token(T_WHITESPACE, '\n');

        $this->assertTrue($token1a->is(T_STRING));
        $this->assertTrue($token1a->is($token1b));
        $this->assertFalse($token1a->is($token2));

        $this->assertTrue($token3->is('='));
        $this->assertTrue($token4->isIgnorable());
        $this->assertTrue($token4->is([$token3, $token4]));
        $this->assertTrue($token4->is([T_WHITESPACE, T_DOC_COMMENT]));
    }
}
