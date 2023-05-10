<?php

namespace Peel\Builder\Nodes;

use \Exception;

class LoopInstruction extends Node
{
    public Node $count;
    public InstructionsList $instruction;

    public function __construct(Node $count, InstructionsList $instruction)
    {
        $this->count= $count;
        $this->instruction = $instruction;
    }

    function toPhp(): string
    {
        throw new Exception("Not implemented");
    }

}