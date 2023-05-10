<?php

namespace Peel\Builder\Nodes;

use \Exception;

class WhileInstruction extends Node
{
    public Node $test;
    public InstructionsList $instruction;

    public function __construct(Node $test, InstructionsList $instruction)
    {
        $this->test= $test;
        $this->instruction = $instruction;
    }

    function toPhp(): string
    {
        throw new Exception("Not implemented");
    }

}