<?php

namespace Peel\Builder\Nodes;


class StringLiteral extends Node
{
    public string $text = "";

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    function toPhp(): string
    {
        return "'" . $this->text . "'";
    }
}