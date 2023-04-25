<?php

namespace Peel\Builder\Nodes;


class BoolLiteral extends Node
{
    public bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    function toPhp(): string
    {
        return (string)$this->value;
    }
}