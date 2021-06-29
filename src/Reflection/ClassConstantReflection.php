<?php

declare(strict_types=1);

namespace PHPStan\Reflection;

use PHPStan\TrinaryLogic;
use PHPStan\Type\ConstantTypeHelper;
use PHPStan\Type\Type;

class ClassConstantReflection implements ConstantReflection
{
    private \PHPStan\Reflection\ClassReflection $declaringClass;

    private \ReflectionClassConstant $reflection;

    private ?string $deprecatedDescription;

    private bool $isDeprecated;

    private bool $isInternal;

    private ?Type $valueType = null;

    public function __construct(
        ClassReflection $declaringClass,
        \ReflectionClassConstant $reflection,
        ?string $deprecatedDescription,
        bool $isDeprecated,
        bool $isInternal
    ) {
        $this->declaringClass = $declaringClass;
        $this->reflection = $reflection;
        $this->deprecatedDescription = $deprecatedDescription;
        $this->isDeprecated = $isDeprecated;
        $this->isInternal = $isInternal;
    }

    public function getName(): string
    {
        return $this->reflection->getName();
    }

    public function getFileName(): ?string
    {
        $fileName = $this->declaringClass->getFileName();
        if ($fileName === false) {
            return null;
        }

        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->reflection->getValue();
    }

    public function getValueType(): Type
    {
        if ($this->valueType === null) {
            $this->valueType = ConstantTypeHelper::getTypeFromValue($this->getValue());
        }
        return $this->valueType;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function isStatic(): bool
    {
        return true;
    }

    public function isPrivate(): bool
    {
        return $this->reflection->isPrivate();
    }

    public function isPublic(): bool
    {
        return $this->reflection->isPublic();
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createFromBoolean($this->isDeprecated);
    }

    public function getDeprecatedDescription(): ?string
    {
        if ($this->isDeprecated) {
            return $this->deprecatedDescription;
        }

        return null;
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createFromBoolean($this->isInternal);
    }

    public function getDocComment(): ?string
    {
        $docComment = $this->reflection->getDocComment();
        if ($docComment === false) {
            return null;
        }

        return $docComment;
    }
}
