<?php

declare(strict_types=1);

namespace PHPStan\Node;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\NodeAbstract;

class InArrowFunctionNode extends NodeAbstract implements VirtualNode
{
    private \PhpParser\Node\Expr\ArrowFunction $originalNode;

    public function __construct(ArrowFunction $originalNode)
    {
        parent::__construct($originalNode->getAttributes());
        $this->originalNode = $originalNode;
    }

    public function getOriginalNode(): \PhpParser\Node\Expr\ArrowFunction
    {
        return $this->originalNode;
    }

    public function getType(): string
    {
        return 'PHPStan_Node_InArrowFunctionNode';
    }

    /**
     * @return string[]
     */
    public function getSubNodeNames(): array
    {
        return [];
    }
}
