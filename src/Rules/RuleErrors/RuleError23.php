<?php

declare(strict_types=1);

namespace PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError23 implements \PHPStan\Rules\RuleError, \PHPStan\Rules\LineRuleError, \PHPStan\Rules\FileRuleError, \PHPStan\Rules\IdentifierRuleError
{
    public string $message;

    public int $line;

    public string $file;

    public string $identifier;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
