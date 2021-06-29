<?php

declare(strict_types=1);

namespace PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError63 implements \PHPStan\Rules\RuleError, \PHPStan\Rules\LineRuleError, \PHPStan\Rules\FileRuleError, \PHPStan\Rules\TipRuleError, \PHPStan\Rules\IdentifierRuleError, \PHPStan\Rules\MetadataRuleError
{
    public string $message;

    public int $line;

    public string $file;

    public string $tip;

    public string $identifier;

    /** @var mixed[] */
    public array $metadata;

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

    public function getTip(): string
    {
        return $this->tip;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return mixed[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
