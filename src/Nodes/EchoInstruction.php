<?php

namespace Peel\Builder\Nodes;

class EchoInstruction extends Node
{
    public function __construct(Node $inner)
    {
        $this->inner = $inner;
    }

    function toPhp(): string
    {
        return "echo ".$this->inner->toPhp();
    }
}