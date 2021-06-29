<?php

declare(strict_types=1);

namespace PHPStan\Rules;

interface MetadataRuleError extends RuleError
{
    /**
     * @return mixed[]
     */
    public function getMetadata(): array;
}
