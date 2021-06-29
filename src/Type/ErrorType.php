<?php

declare(strict_types=1);

namespace PHPStan\Type;

class ErrorType extends MixedType
{
    public function __construct()
    {
        parent::__construct();
    }

    public function describe(VerbosityLevel $level): string
    {
        return $level->handle(
            function () use ($level): string {
                return parent::describe($level);
            },
            function () use ($level): string {
                return parent::describe($level);
            },
            static function (): string {
                return '*ERROR*';
            }
        );
    }

    public function getIterableKeyType(): Type
    {
        return new ErrorType();
    }

    public function getIterableValueType(): Type
    {
        return new ErrorType();
    }

    public function subtract(Type $type): Type
    {
        return new self();
    }

    public function equals(Type $type): bool
    {
        return $type instanceof self;
    }

    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties): Type
    {
        return new self();
    }
}
