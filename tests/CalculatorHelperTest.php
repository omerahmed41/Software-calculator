<?php


namespace App\Tests;


use App\Helper\CalculatorHelper;
use App\Helper\Math;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class CalculatorHelperTest extends WebTestCase
{
// todo: add unit test cases for CalculatorHelperTest
// todo: add functional test cases for CalculatorHelperTest
    public function testIsRightFormat()
    {

        $this->assertEquals(false, false);

    }

}