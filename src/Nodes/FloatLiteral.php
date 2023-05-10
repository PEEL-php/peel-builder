<?php

namespace Peel\Builder\Nodes;


class FloatLiteral extends Node
{
    public float $value = 0;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    function toPhp(): string
    {
        return  $this->value;
    }
}