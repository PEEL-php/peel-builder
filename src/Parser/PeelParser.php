<?php

namespace Peel\Builder\Parser;

use Peel\Builder\Nodes\BoolLiteral;
use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\FloatLiteral;
use Peel\Builder\Nodes\IfElseInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\IntegerLiteral;
use Peel\Builder\Nodes\LoopInstruction;
use Peel\Builder\Nodes\Node;
use Peel\Builder\Nodes\StringLiteral;
use Peel\Builder\Nodes\WhileInstruction;

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

    public static function ParseExpression(string $text)
    {
        return (new PeelParser($text))->parseNormal();
    }

    private function parseInstructionsList($exitLevel = 0)
    {
        $ret = new InstructionsList();

        while ($this->position < strlen($this->text)) {
            $this->skipWhite();
            if ($this->char() == '}') {
                if ($exitLevel == 0) $this->error("Unexpected }");
                else {
                    $this->position++;
                    break;
                }
            }
            $inst = $this->parseNormal();
            $ret->list[] = $inst;
            $this->skipWhite();
            if (!($inst instanceof InstructionsList) && !($inst instanceof IfElseInstruction) && !($inst instanceof LoopInstruction) && $this->char() != ';') $this->error();
            else $this->position++;
        }
        return $ret;
    }

    private function parseNormal($exitLevel = 0): ?Node
    {
        $ret = null;
        while ($this->position < strlen($this->text)) {
            $this->skipWhite();
            if ($this->position >= strlen($this->text)) break;
            $char = $this->text[$this->position];
            if ($this->isNextWord('echo')) {
                $this->position += 5;
                $inner = $this->parseNormal(1);
                $ret = new EchoInstruction($inner);
            } else if ($this->isNextWord('loop')) {
                $this->position += 4;
                list($count, $instructions) = $this->parseIfContent();
                $ret = new LoopInstruction($count, $instructions);
            } else if ($this->isNextWord('while')) {
                $this->position += 5;
                list($test, $instructions) = $this->parseIfContent();
                $ret = new WhileInstruction($test, $instructions);
            } else if ($this->isNextWord('if')) {
                $this->position += 2;
                list($condition, $instructions) = $this->parseIfContent();
                $ret = new IfElseInstruction([(object)['condition' => $condition, 'instruction' => $instructions]]);
            } else if ($this->isNextWord('else')) {
                if (!($ret instanceof IfElseInstruction)) $this->error("Unexpected else");
                $this->position += 4;
                $this->skipWhite();
                if ($this->isNextWord('if')) {
                    $this->position += 2;
                    list($condition, $instructions) = $this->parseIfContent();
                    $ret->conditions[] = (object)['condition' => $condition, 'instruction' => $instructions];
                } else {
                    $ret->elseInstruction = $this->parseNormal();
                }
            } else if (preg_match("/[+\-0-9\.]/", $char)) {
                $value = $this->readWhile("/^([+-]|[+-]?\.|[+-]?[0-9]?\.?[0-9])$/");
                $int = intval($value);
                $float = floatval($value);
                if ($int == $float)
                    $ret = new IntegerLiteral($int);
                else
                    $ret = new FloatLiteral($float);
            } else if ($this->char() == '"') {
                $this->position += 1;
                $text = "";
                while ($this->position < strlen($this->text)) {
                    if ($this->char() == '"') {
                        $this->position++;
                        break;
                    } else
                        $text .= $this->char();
                    $this->position++;
                }
                $ret = new StringLiteral($text);
                break;
            } else if ($this->char() == "'") {
                $this->position += 1;
                $text = "";
                while ($this->position < strlen($this->text)) {
                    if ($this->char() == "'") {
                        $this->position++;
                        break;
                    } else
                        $text .= $this->char();
                    $this->position++;
                }
                $ret = new StringLiteral($text);
                break;
            } else if ($this->char() == ';') {
                break;
            } else if ($this->char() == ')') {
                if ($exitLevel == 10)
                    break; else {
                    $this->error("Unexpected )");
                }
            } else if ($this->char() == '{') {
                $this->position += 1;
                $ret = $this->parseInstructionsList(1);
                break;
            } else if ($this->isNextWord('true')) {
                $this->position += 4;
                $ret = new BoolLiteral(true);
            } else if ($this->isNextWord('false')) {
                $this->position += 5;
                $ret = new BoolLiteral(false);
            } else {
                $this->error("Unknown character $char");
            }
        }
        return $ret;
    }

    private function isNextWord(string $word)
    {
        return substr($this->text, $this->position, strlen($word)) == $word && preg_match('/[ \(\){}]/', $this->text[$this->position + strlen($word)]);
    }

    private function char()
    {
        return $this->text[$this->position];
    }

    private function skipWhite()
    {
        while ($this->position < strlen($this->text) && preg_match('/\\s/', $this->char())) {
            $this->position++;
        }
    }

    private function error(string $string)
    {
        throw new \Exception($string);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function parseIfContent(): array
    {
        $this->skipWhite();
        if ($this->char() != '(') {
            $this->error("Expected ( after if");
        }
        $this->position += 1;
        $condition = $this->parseNormal(10);

        $this->skipWhite();
        if ($this->char() != ')') {
            $this->error("Expected )");
        }
        $this->position += 1;
        $instructions = $this->parseNormal();
        return array($condition, $instructions);
    }

    protected function readUntill($regexp)
    {
        $ret = "";

        while ($this->position < strlen($this->text)) {
            $char = $this->text[$this->position];
            if (preg_match($regexp, $char)) break;
            $ret .= $char;
            $this->position++;
        }
        return $ret;
    }

    protected function readWhile($regexp)
    {
        $ret = "";
        while ($this->position < strlen($this->text)) {
            $char = $this->text[$this->position];
            if (!preg_match($regexp, $ret . $char)) break;
            $ret .= $char;
            $this->position++;
        }
        return $ret;
    }

}