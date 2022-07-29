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
        $obj = PeelParser::Parse('echo "Hello, world!";');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[0]->inner);
        $this->assertEquals("Hello, world!",  $obj->list[0]->inner->text);
    }
}
