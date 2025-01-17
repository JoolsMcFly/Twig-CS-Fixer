<?php

declare(strict_types=1);

namespace TwigCsFixer\Sniff;

use TwigCsFixer\Token\Token;

/**
 * Ensure there is one space before {{, {%, {#, and after }}, %} and #}.
 */
final class DelimiterSpacingSniff extends AbstractSpacingSniff
{
    /**
     * @param list<Token> $tokens
     */
    protected function shouldHaveSpaceBefore(int $tokenPosition, array $tokens): ?int
    {
        $token = $tokens[$tokenPosition];

        if (
            $this->isTokenMatching($token, Token::VAR_END_TYPE)
            || $this->isTokenMatching($token, Token::BLOCK_END_TYPE)
            || $this->isTokenMatching($token, Token::COMMENT_END_TYPE)
        ) {
            return 1;
        }

        return null;
    }

    /**
     * @param list<Token> $tokens
     */
    protected function shouldHaveSpaceAfter(int $tokenPosition, array $tokens): ?int
    {
        $token = $tokens[$tokenPosition];

        if (
            $this->isTokenMatching($token, Token::VAR_START_TYPE)
            || $this->isTokenMatching($token, Token::BLOCK_START_TYPE)
            || $this->isTokenMatching($token, Token::COMMENT_START_TYPE)
        ) {
            return 1;
        }

        return null;
    }
}
