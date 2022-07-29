<?php

namespace Peel\Builder\Nodes;


use MKrawczyk\FunQuery\FunQuery;

class InstructionsList extends Node
{

    public array $list=[];

    function toPhp(): string
    {
        return implode("", FunQuery::create($this->list)->map(fn($x)=>$x->toPhp().";\r\n")->toArray());
    }
}