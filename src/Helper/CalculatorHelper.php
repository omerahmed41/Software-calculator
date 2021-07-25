<?php


namespace App\Helper;


use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use Psr\Log\LoggerInterface;

class CalculatorHelper
{

    private Logger $logger;
    private $em;

    public function __construct(LoggerInterface $logger, $em)
    {
        $this->logger = new Logger($logger);
        $this->em = $em;
    }

    function startCLILogger(){
                $this->logger->cliMode = true;
    }

    function getUserInput(){
        // echo "cal";
        echo "Enter a math expression example 1+2*6: \n ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        $this->handleInput(trim($line));
        echo "\n";
        echo "Thank you, ...\n";
    }



    function handleInput($string,$cli = false ): array
    {
//    $string = 'omer(21)+round(5)';

        $this->logger->print_message("Your input is: $string \n");

        $parsedInput = $this->validateInput($string);
        if (!$parsedInput['state']) {
            $this->logger->print_message("Wrong input \n", 'error');
            return $this->returnResponse(false, "Wrong input \n");

        }


        $calculationResult = $this->runCalculation($parsedInput['message']);
        if (!$calculationResult['state']) {
            $this->logger->print_message($calculationResult['message'], 'error');
            return $this->returnResponse(false, $calculationResult['message']);

        }
        $res =$calculationResult['message'];

        [$result,$steps,$baseOperations,$functions] = $res;
        $entityManager = $this->em;

        $equation = new EquationLog();
        $equation->setEquation($string);
        $equation->setResult($result);
        $equation->setSteps(json_encode($steps));
        $entityManager->persist($equation);

        $allOperations = array_merge($baseOperations,$functions);
        foreach ($allOperations as $baseOperation){
            $operation = new OperationsLog();
            $operation->setOperationType($baseOperation);
            $operation->setEquation($equation);
            $entityManager->persist($operation);

        }

        $entityManager->flush();
        $this->logger->print_message($steps, 'success');

      return  $res;

    }

    function runCalculation($parsedInput): array
    {

        [$nunOperations, $baseOperations] = $parsedInput;


        $calculateFunctions = $this->calculateFunctions($nunOperations);
        if (!$calculateFunctions['state']) {
            $this->logger->print_message($calculateFunctions['message'], 'error');
            return $this->returnResponse(false, $calculateFunctions['message']);

        }

        $functions = $calculateFunctions['functions'];
        $nums = $calculateFunctions['result'];

        $calculateOperations = $this->calculateOperations($nums, $baseOperations);
        if (!$calculateOperations['state']) {
            $this->logger->print_message($calculateOperations['message'], 'error');
            return $this->returnResponse(false, $calculateOperations['message']);

        }
        $result = $calculateOperations['result'];
        $steps = array_merge($calculateFunctions['message'], $calculateOperations['message']);
        $res = [$result,$steps,$baseOperations,$functions];
        $this->logger->print_message("Result: $result", 'success');
        return  $this->returnResponse(true, $res);
    }

    function calculateOperations($nums, $operations): array
    {
        $m = new Math;

        $operations = array_values($operations);
        $nums = array_values($nums);

        $steps = [];
        // do * and / first
        foreach ($operations as $key => $opt) {
            if ($opt == '*' || $opt == "/") {
                $key+=1;
                $equation = $nums[$key - 1]. $opt.  $nums[$key] ."= ";
                $this->logger->print_message($equation);

                $calc = $m->calc($opt, [$nums[$key - 1], $nums[$key]]);
                if (!$calc['state']) {
                    $this->logger->print_message($calc['message'], 'error');
                    return $calc;
                }
                $result = $calc['result'];
                $this->logger->print_message($result ."\n");

                // array_shift($nums);
                unset($operations[$key-1]);

                $nums[$key] = $result;
                unset($nums[$key - 1]);
                array_push($steps, $equation.$result);
//        array_shift($nums);

            }
        }
        $nums = array_values($nums);
        $operations = array_values($operations);

        // do + and -
        // init $result using first number
        $result = $nums[0];
        array_shift($nums);
        foreach ($operations as $key => $opt) {
            $equation = "$result $opt $nums[0] =";

            $this->logger->print_message($equation);

            $calc = $m->calc($opt, [$result, $nums[0]]);
            if (!$calc['state']) {
                $this->logger->print_message($calc['message'], 'error');
                return $calc;
            }
            $result = $calc['result'];
            $this->logger->print_message($result. "\n");

            // array_shift($nums);
            array_shift($nums);
            array_push($steps, $equation.$result);
        }
        return $this->returnResponse(true, $steps, $result);
    }

    function calculateFunctions($nunOperations): array
    {
        $steps = [];
        $m = new Math;
        $functions = [];
        foreach ($nunOperations as $key => $nunOperation) {
            preg_match("/$m->allowedFunctions/", $nunOperation, $funName, PREG_OFFSET_CAPTURE);
            if (!$funName) {
                continue;
            }

            array_push($functions,$funName[0][0]);
            $this->logger->print_message('funName: '. "\n".$funName[0][0]);


            $arguments = array_values(array_filter(preg_split("/[^0-9]/", $nunOperation)));
            foreach ($arguments as $k => $argument) {
                $this->logger->print_message(",argument[$k]: $argument");

            }
            $result = $m->calc($funName[0][0], $arguments);
            if (!$result['state']) {
                return $result;
            }
            $nunOperations[$key] = $result['result'];

            array_push($steps,"$nunOperation = ".$result['result']);

        }
        $this->logger->print_message("numbers array:");
        $this->logger->print_message($nunOperations);

        return [
            'state' => true,
            'result' => $nunOperations,
            'functions' => $functions,
            'message' => $steps,
        ];

    }

    function validateInput($string)
    {

        if (!$this->hasUnallowableChar($string)) {
            $this->logger->print_message('Has Unallowable Chars', 'error');
            return $this->returnResponse(false,'Has Unallowable Chars');

        }

        [$nunOperations, $operations] = $this->phraseInput($string);

        if (!$this->isRightFormat($nunOperations, $operations)) {
            $this->logger->print_message("wrong format \n");
            return $this->returnResponse(false,"wrong format \n");


        }
        return $this->returnResponse(true, [$nunOperations, $operations]);

    }

    function isRightFormat($nunOperations, $operations): bool
    {
        if (count($nunOperations) - count($operations) != 1) {
            return false;
        }
        return true;
    }

    /**
     * @param $string
     * @return array
     */
    function phraseInput($string): array
    {
        $optPattern = "\+*\/\-";

//    preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE);

        $nunOperations = array_filter(preg_split("/[$optPattern]/", $string));


        $operations = array_filter(preg_split("/[^$optPattern]/", $string));
        //    $nums = array_filter(preg_split("/[^0-9]/",$string));
        $this->logger->print_message($nunOperations);
        $this->logger->print_message($operations);

        return [$nunOperations, $operations];
    }

    function hasUnallowableChar($string): bool
    {

        $m = new Math;


        $number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // What is a number
//    $functions = '(?:sinh?|cosh?|tanh?|abs|acosh?|asinh?|atanh?|exp|log10|deg2rad|rad2deg|sqrt|ceil|floor|round)'
//        .'\s*\((?1)+\)|\((?1)+\))(?:'; // Allowed PHP functions
        $functions = "$m->allowedFunctions"
            . '\s*\((?1)+\)|\((?1)+\))(?:'; // Allowed local functions
        $operators = '[+\/*\^%-]'; // Allowed math operators

        $regexp = '/^((' . $number . '|' . $functions . '|' . $operators . ')?)+$/'; // Final regexp, heavily using recursive patterns

        if (preg_match($regexp, $string)) {
            return true;
        }
        return false;
    }


    /**
     * @param false $state
     * @param null $message
     * @param null $result
     * @return array
     */
    function returnResponse($state = false, $message = null, $result = null): array
    {
        return [
            'state' => $state,
            'message' => $message,
            'result' => $result,
        ];
    }

    


}

