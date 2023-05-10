<?php

namespace Literals;

use AssingmentInstruction;
use ComparsionInstruction;
use DecrementationInstruction;
use ForInstruction;
use Peel\Builder\Nodes\BoolLiteral;
use Peel\Builder\Nodes\EchoInstruction;
use Peel\Builder\Nodes\FloatLiteral;
use Peel\Builder\Nodes\IfElseInstruction;
use Peel\Builder\Nodes\InstructionsList;
use Peel\Builder\Nodes\IntegerLiteral;
use Peel\Builder\Nodes\LoopInstruction;
use Peel\Builder\Nodes\StringLiteral;
use Peel\Builder\Parser\PeelParser;
use PHPUnit\Framework\TestCase;
use Variable;


class  LiteralsParserTest extends TestCase
{
    public function testInteger()
    {
        $obj = PeelParser::ParseExpression('123');
        $this->assertInstanceOf(IntegerLiteral::class, $obj);
        $this->assertEquals(123, $obj->value);
    }

    public function testLongInteger()
    {
        $obj = PeelParser::ParseExpression('12345678901234567890');
        $this->assertInstanceOf(FloatLiteral::class, $obj);//for php integer over 32 bit is float, so in PEEL we keeps it the same
        $this->assertEquals(12345678901234567890, $obj->value);
    }

    public function testNegativeInteger()
    {
        $obj = PeelParser::ParseExpression('-123');
        $this->assertInstanceOf(IntegerLiteral::class, $obj);
        $this->assertEquals(-123, $obj->value);
    }

    public function testFloat()
    {
        $obj = PeelParser::ParseExpression('123.456');
        $this->assertInstanceOf(FloatLiteral::class, $obj);
        $this->assertEquals(123.456, $obj->value);
    }

    public function testStringDouble()
    {
        $obj = PeelParser::ParseExpression('"123"');
        $this->assertInstanceOf(StringLiteral::class, $obj);
        $this->assertEquals("123", $obj->text);
    }

    public function testStringSingle()
    {
        $obj = PeelParser::ParseExpression("'123'");
        $this->assertInstanceOf(StringLiteral::class, $obj);
        $this->assertEquals("123", $obj->text);
    }

    public function testBoolTrue()
    {
        $obj = PeelParser::ParseExpression('true');
        $this->assertInstanceOf(BoolLiteral::class, $obj);
        $this->assertEquals(true, $obj->value);
    }

    public function testBoolFalse()
    {
        $obj = PeelParser::ParseExpression('false');
        $this->assertInstanceOf(BoolLiteral::class, $obj);
        $this->assertEquals(false, $obj->value);
    }
}
