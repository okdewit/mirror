<?php declare(strict_types=1);

namespace Okdewit\Mirror;

class Token
{
    /** One of the T_* constants, or an integer < 256 representing a single-char token. */
    public int $id;
    /** The textual content of the token. */
    public string $text;
    /** The starting line number (1-based) of the token. */
    public int $line;
    /** The starting position (0-based) in the tokenized string. */
    public int $pos;

    public function __construct(int $id, string $text, int $line = -1, int $pos = -1)
    {
        $this->id = ($id < 265) ? ord($text) : $id;
        $this->text = $text;
        $this->line = $line;
        $this->pos = $pos;
    }

    /**
     * @param string|array $basetoken
     * @return Token
     */
    public static function fromBasetoken($basetoken): self
    {
        if (is_string($basetoken)) return new self(0, $basetoken);
        return new self($basetoken[0], $basetoken[1], $basetoken[2]);
    }

    public function getTokenName(): ?string
    {
        if ($this->id < 256) {
            return chr($this->id);
        } elseif ('UNKNOWN' !== $name = token_name($this->id)) {
            return $name;
        }

        return null;
    }

    /**
     * @param int|string|array $kind
     * @return bool
     */
    public function is($kind): bool {
        if (is_array($kind)) {
            foreach ($kind as $k) if ($this->is($k)) return true;
        }

        if ($kind instanceof Token) {
            return ($this->text === $kind->text) && $this->is($kind->id);
        }

        if (($this->id === $kind) || ($this->getTokenName() === $kind)) return true;

        return false;
    }

    public function isIgnorable(): bool {
        return $this->is([
            T_WHITESPACE,
            T_COMMENT,
            T_DOC_COMMENT,
            T_OPEN_TAG,
        ]);
    }
}
