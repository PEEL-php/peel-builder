<?php

use Peel\Builder\Nodes\BoolLiteral;
use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\IfElseInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\StringLiteral;
use Peel\Builder\Parser\PeelParser;
use PHPUnit\Framework\TestCase;


class  StructureParserTest extends TestCase
{
    public function testIf()
    {
        $obj = PeelParser::Parse('if(true){
    echo "if";
}else if (false)
    {echo "else if";}

else{
    echo "else";}
');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(IfElseInstruction::class, $obj->list[0]);

        $this->assertInstanceOf(BoolLiteral::class, $obj->list[0]->conditions[0]->condition);
        $this->assertInstanceOf(InstructionsList::class, $obj->list[0]->conditions[0]->instruction);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]->conditions[0]->instruction->list[0]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[0]->conditions[0]->instruction->list[0]->inner);
        $this->assertEquals("if", $obj->list[0]->conditions[0]->instruction->list[0]->inner->text);

        $this->assertInstanceOf(BoolLiteral::class, $obj->list[0]->conditions[1]->condition);
        $this->assertInstanceOf(InstructionsList::class, $obj->list[0]->conditions[1]->instruction);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]->conditions[1]->instruction->list[0]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[0]->conditions[1]->instruction->list[0]->inner);
        $this->assertEquals("else if", $obj->list[0]->conditions[1]->instruction->list[0]->inner->text);

        $this->assertInstanceOf(InstructionsList::class, $obj->list[0]->elseInstruction);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]->elseInstruction->list[0]);
        $this->assertInstanceOf(StringLiteral::class, $obj->list[0]->elseInstruction->list[0]->inner);
        $this->assertEquals("else", $obj->list[0]->elseInstruction->list[0]->inner->text);
    }
    public function testBadIf(){
        $this->expectExceptionMessage("Expected ( after if");
        PeelParser::Parse('if true');
    }
}
