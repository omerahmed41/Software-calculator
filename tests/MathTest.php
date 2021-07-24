<?php


namespace App\Tests;

use App\Helper\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{

    public function testUsingURegisteredOpt()
    {
        $m = new Math;

        $opt = "@#$";
        $result = $m->calc($opt, [1 , 4]);

        // todo: add translations
        $expectedResult= [
            'state' => false,
            'message' => "$opt not in our Database"
        ];

        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testAdd()
    {
        $m = new Math;

        $result = $m->calc("+", [1 , 4]);

        $expectedResult = [
            'state' => true,
            'result' => 5
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testSubtract()
    {
        $m = new Math;

        $result = $m->calc("-", [1 , 4]);

        $expectedResult = [
            'state' => true,
            'result' => -3
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testMultiple()
    {
        $m = new Math;

        $result = $m->calc("*", [1 , 4]);

        $expectedResult = [
            'state' => true,
            'result' => 4
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testDivide()
    {
        $m = new Math;

        $result = $m->calc("/", [1 , 4]);

        $expectedResult = [
            'state' => true,
            'result' => 0.25
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testSquareRoot()
    {
        $m = new Math;

        $result = $m->calc("square", [4]);

        $expectedResult = [
            'state' => true,
            'result' => 16
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }
    public function testCubicRoot()
    {
        $m = new Math;

        $result = $m->calc("cubic", [3]);

        $expectedResult = [
            'state' => true,
            'result' => 27
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testPowerRoot()
    {
        $m = new Math;

        $result = $m->calc("power", [2,4]);

        $expectedResult = [
            'state' => true,
            'result' => 16
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }

    public function testFactorial()
    {
        $m = new Math;

        $result = $m->calc("factorial", [3]);

        $expectedResult = [
            'state' => true,
            'result' => 6
        ];
        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $result);
    }


}