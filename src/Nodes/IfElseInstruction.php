<?php

namespace Peel\Builder\Nodes;

use \Exception;

class IfElseInstruction extends Node
{
    public array $conditions;
    public ?InstructionsList $elseInstruction;

    public function __construct(array $conditions, ?InstructionsList $elseInstruction = null)
    {
        $this->conditions = $conditions;
        $this->elseInstruction = $elseInstruction;
    }

    function toPhp(): string
    {
        throw new Exception("Not implemented");
    }

}