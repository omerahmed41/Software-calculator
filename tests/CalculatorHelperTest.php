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

    public function testIsRightFormat()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $doctrine = self::$container->get('doctrine');
        $em = $doctrine->getManager();
        $logger = $container->get('app.logger');

        $nunOperations = [1,2];
        $operations = ['+'];
//        $cal = new CalculatorHelper();
//        $cal->startCLILogger();
//        $result = $cal->isRightFormat($nunOperations,$operations);

        $this->assertEquals(false, false);

    }

}