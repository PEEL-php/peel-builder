<?php

use Peel\Builder\Nodes\BoolLiteral;
use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\IfElseInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\IntegerLiteral;
use Peel\Builder\Nodes\LoopInstruction;
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

    public function testBadIf()
    {
        $this->expectExceptionMessage("Expected ( after if");
        PeelParser::Parse('if true');
    }

    public function testBadIf2()
    {
        $this->expectExceptionMessage("Expected instruction after if");
        PeelParser::Parse('if (true)');
    }

    public function testBadIf3()
    {
        $this->expectExceptionMessage("Unclosed bracket");
        PeelParser::Parse('if (true){');
    }

    public function testLoop()
    {
        $obj = PeelParser::Parse('loop(10){
echo "loop";
}');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(LoopInstruction::class, $obj->list[0]);

        $this->assertInstanceOf(IntegerLiteral::class, $obj->list[0]->count);
        $this->assertEquals(10, $obj->list[0]->count->value);
        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]->instruction->list[0]);
        $this->assertEquals("loop", $obj->list[0]->instruction->list[0]->inner->text);
    }

    public function testBadLoop()
    {
        $this->expectExceptionMessage("Expected ( after loop");
        PeelParser::Parse('loop 10');
    }

    public function testBadLoop2()
    {
        $this->expectExceptionMessage("Expected instruction after loop");
        PeelParser::Parse('loop (10)');
    }

    public function testBadLoop3()
    {
        $this->expectExceptionMessage("Unclosed bracket");
        PeelParser::Parse('loop (10){');
    }

    public function testFor()
    {
        $obj = PeelParser::Parse('for (i = 99; i > 1; i--){echo i;}');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(ForInstruction::class, $obj->list[0]);

        $this->assertInstanceOf(AssingmentInstruction::class, $obj->list[0]->start);
        $this->assertInstanceOf(IntegerLiteral::class, $obj->list[0]->start->source);
        $this->assertEquals(99, $obj->list[0]->start->source->value);
        $this->assertInstanceOf(Variable::class, $obj->list[0]->start->destination);
        $this->assertEquals('i', $obj->list[0]->start->destination->name);


        $this->assertInstanceOf(ComparsionInstruction::class, $obj->list[0]->test);
        $this->assertInstanceOf(Variable::class, $obj->list[0]->test->left);
        $this->assertEquals('i', $obj->list[0]->test->left->name);
        $this->assertInstanceOf(IntegerLiteral::class, $obj->list[0]->test->right);
        $this->assertEquals(1, $obj->list[0]->test->right->value);


        $this->assertInstanceOf(DecrementationInstruction::class, $obj->list[0]->each);
        $this->assertTrue($obj->list[0]->each->after);
        $this->assertInstanceOf(Variable::class, $obj->list[0]->each->item);
        $this->assertEquals('i', $obj->list[0]->each->item->name);


        $this->assertInstanceOf(EchoInstruction::class, $obj->list[0]->instruction->list[0]);
        $this->assertEquals("i", $obj->list[0]->instruction->list[0]->inner->name);
    }

    public function testBadFor()
    {
        $this->expectExceptionMessage("Expected ( after for");
        PeelParser::Parse('for i = 99; i > 1; i--){echo i;}');
    }

    public function testWhile()
    {
        $obj = PeelParser::Parse('while(true){break;}');
        $this->assertInstanceOf(InstructionsList::class, $obj);
        $this->assertInstanceOf(WhileInstruction::class, $obj->list[0]);
        $this->assertInstanceOf(BoolLiteral::class, $obj->list[0]->test);
        $this->assertTrue($obj->list[0]->test->value);

        $this->assertInstanceOf(BreakInstruction::class, $obj->list[0]->instruction->list[0]);


    }
}
