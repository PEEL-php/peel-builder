<?php


use Peel\Builder\PeelRuntime;
use PHPUnit\Framework\TestCase;

class BasicExecutionTest extends TestCase
{

    public function testHelloWorld()
    {
        ob_start();
        PeelRuntime::includePeelString('echo "Hello, world!";');
        $this->assertEquals("Hello, world!", ob_get_contents());
        ob_end_clean();
    }

}