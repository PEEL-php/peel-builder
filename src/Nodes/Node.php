<?php

namespace Peel\Builder\Nodes;

abstract class Node
{
    abstract function toPhp(): string;
}