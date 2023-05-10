<?php

namespace Peel\Builder\Nodes;


class IntegerLiteral extends Node
{
    public int $value = 0;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    function toPhp(): string
    {
        return  $this->value;
    }
}