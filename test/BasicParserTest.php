<?php

use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\StringLiteral;
use Peel\Builder\Parser\PeelParser;
use PHPUnit\Framework\TestCase;


class  BasicParserTest extends TestCase
{
    public function testBasicText()
    {
        $obj = PeelParser::Parse('echo "Hello, ";' . "echo 'world!';");
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[0]->inner);
        $this->assertEquals("Hello, ", $obj->list[0]->inner->text);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[1]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[1]->inner);
        $this->assertEquals("world!", $obj->list[1]->inner->text);
    }

    public function testBrackets()
    {
        $obj = PeelParser::Parse('echo "";{echo "";}');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]);
        $this->assertInstanceOf(InstructionsList::class, $obj->list[1]);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[1]->list[0]);
    }
}
