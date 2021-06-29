<?php

declare(strict_types=1);

namespace PHPStan\Rules\Properties;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\ClassNameNodePair;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\ClassPropertyNode>
 */
class ExistingClassesInPropertiesRule implements \PHPStan\Rules\Rule
{
    private \PHPStan\Reflection\ReflectionProvider $reflectionProvider;

    private \PHPStan\Rules\ClassCaseSensitivityCheck $classCaseSensitivityCheck;

    private bool $checkClassCaseSensitivity;

    private bool $checkThisOnly;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        ClassCaseSensitivityCheck $classCaseSensitivityCheck,
        bool $checkClassCaseSensitivity,
        bool $checkThisOnly
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->classCaseSensitivityCheck = $classCaseSensitivityCheck;
        $this->checkClassCaseSensitivity = $checkClassCaseSensitivity;
        $this->checkThisOnly = $checkThisOnly;
    }

    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$scope->isInClass()) {
            throw new \PHPStan\ShouldNotHappenException();
        }

        $propertyReflection = $scope->getClassReflection()->getNativeProperty($node->getName());
        if ($this->checkThisOnly) {
            $referencedClasses = $propertyReflection->getNativeType()->getReferencedClasses();
        } else {
            $referencedClasses = array_merge(
                $propertyReflection->getNativeType()->getReferencedClasses(),
                $propertyReflection->getPhpDocType()->getReferencedClasses()
            );
        }

        $errors = [];
        foreach ($referencedClasses as $referencedClass) {
            if ($this->reflectionProvider->hasClass($referencedClass)) {
                if ($this->reflectionProvider->getClass($referencedClass)->isTrait()) {
                    $errors[] = RuleErrorBuilder::message(sprintf(
                        'Property %s::$%s has invalid type %s.',
                        $propertyReflection->getDeclaringClass()->getDisplayName(),
                        $node->getName(),
                        $referencedClass
                    ))->build();
                }
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Property %s::$%s has unknown class %s as its type.',
                $propertyReflection->getDeclaringClass()->getDisplayName(),
                $node->getName(),
                $referencedClass
            ))->discoveringSymbolsTip()->build();
        }

        if ($this->checkClassCaseSensitivity) {
            $errors = array_merge(
                $errors,
                $this->classCaseSensitivityCheck->checkClassNames(array_map(static function (string $class) use ($node): ClassNameNodePair {
                    return new ClassNameNodePair($class, $node);
                }, $referencedClasses))
            );
        }

        return $errors;
    }
}
