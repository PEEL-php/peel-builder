<?php

namespace Peel\Builder\Parser;

use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\Node;
use Peel\Builder\Nodes\StringLiteral;

class PeelParser
{
    public function __construct(string $text)
    {
        $this->text = $text;
        $this->position = 0;
    }

    public static function Parse(string $text)
    {
        return (new PeelParser($text))->parseInstructionsList();
    }

    private function parseInstructionsList($exitLevel = 0)
    {
        $ret = new InstructionsList();

        while ($this->position < strlen($this->text)) {
            $this->skipWhite();
            $inst = $this->parseNormal();
            $ret->list[] = $inst;
            $this->skipWhite();
            if ($this->char() != ';') $this->error();
            else $this->position++;
        }
        return $ret;
    }

    private function parseNormal($exitLevel = 0): ?Node
    {
        $ret = null;
        while ($this->position < strlen($this->text)) {
            $char = $this->text[$this->position];
            if ($this->isNextWord('echo')) {
                $this->position += 5;
                $inner = $this->parseNormal(1);
                $ret = new EchoInstruction($inner);
            } else if ($this->char() == '"') {
                $this->position += 1;
                $text = "";
                while ($this->position < strlen($this->text)) {
                    if ($this->char() == '"') {
                        $this->position++;
                        break;
                    }
                    else
                        $text .= $this->char();
                    $this->position++;
                }
                $ret = new StringLiteral($text);
                break;
            } else if ($this->char() == ';') {
                break;
            } else {
                $this->error("Unknown character $char");
            }
        }
        return $ret;
    }

    private function isNextWord(string $word)
    {
        return substr($this->text, $this->position, strlen($word)) == $word && $this->text[$this->position + strlen($word)] == ' ';
    }

    private function char()
    {
        return $this->text[$this->position];
    }

    private function skipWhite()
    {
        while ($this->position < strlen($this->text) && $this->char() == ' ') {
            $this->position++;
        }
    }
}