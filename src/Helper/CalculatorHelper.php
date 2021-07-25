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

    /**
     * to enable or disable the print command
     */
    function startCLILogger(){
                $this->logger->cliMode = true;
    }


    function handleInput($string ): array
    {

        $this->logger->print_message("Your input is: $string \n");

        $parsedInput = $this->validateInput($string);
        if (!$parsedInput['state']) {
            // todo: throw error to handle errors one time
            $this->logger->print_message("Wrong input \n", 'error');
            return $this->returnResponse(false, "Wrong input \n");
        }


        $calculationResult = $this->runCalculation($parsedInput['message']);
        if (!$calculationResult['state']) {
            // todo: throw error to handle errors one time
            $this->logger->print_message($calculationResult['message'], 'error');
            return $this->returnResponse(false, $calculationResult['message']);

        }
        $res =$calculationResult['message'];
        $steps = $res[1];
        $this->saveEquationsTODaTODO($res,$string);
        $this->logger->print_message($steps, 'success');


      return $this->returnResponse(true,null,$res ) ;

    }



    function runCalculation($parsedInput): array
    {

        [$nunOperations, $baseOperations] = $parsedInput;


        $calculateFunctions = $this->calculateFunctions($nunOperations);
        if (!$calculateFunctions['state']) {
            // todo: throw error to handle errors one time
            $this->logger->print_message($calculateFunctions['message'], 'error');
            return $this->returnResponse(false, $calculateFunctions['message']);

        }

        $functions = $calculateFunctions['functions'];
        $nums = $calculateFunctions['result'];

        $calculateOperations = $this->calculateOperations($nums, $baseOperations);
        if (!$calculateOperations['state']) {
            // todo: throw error to handle errors one time
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
//                $this->logger->print_message($equation);

                $calc = $m->calc($opt, [$nums[$key - 1], $nums[$key]]);
                if (!$calc['state']) {
                    $this->logger->print_message($calc['message'], 'error');
                    return $calc;
                }
                $result = $calc['result'];
//                $this->logger->print_message($result ."\n");

                // array_shift($nums);
                unset($operations[$key-1]);

                $nums[$key] = $result;
                unset($nums[$key - 1]);
                array_push($steps, $equation.$result);
                $this->logger->print_message($equation.$result);

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

//            $this->logger->print_message($equation);

            $calc = $m->calc($opt, [$result, $nums[0]]);
            if (!$calc['state']) {
                // todo: throw error to handle errors one time
                $this->logger->print_message($calc['message'], 'error');
                return $calc;
            }
            $result = $calc['result'];
//            $this->logger->print_message($result. "\n");

            // array_shift($nums);
            array_shift($nums);
            array_push($steps, $equation.$result);
            $this->logger->print_message($equation.$result);

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

            $arguments = array_values(array_filter(preg_split("/[^0-9]/", $nunOperation)));
            $result = $m->calc($funName[0][0], $arguments);
            if (!$result['state']) {
                return $result;
            }
            $nunOperations[$key] = $result['result'];

            array_push($steps,"$nunOperation = ".$result['result']);
            $this->logger->print_message("$nunOperation = ".$result['result']);

        }

        return [
            'state' => true,
            'result' => $nunOperations,
            'functions' => $functions,
            'message' => $steps,
        ];

    }

    /**
     * @param $string
     * @return array
     */
    function validateInput($string)
    {

        if (!$this->hasUnallowableChar($string)) {
            // todo: throw error to handle errors one time
            $this->logger->print_message('Has Unallowable Chars', 'error');
            return $this->returnResponse(false,'Has Unallowable Chars');

        }

        [$nunOperations, $operations] = $this->phraseInput($string);

        if (!$this->isRightFormat($nunOperations, $operations)) {
            // todo: throw error to handle errors one time
            $this->logger->print_message("wrong format \n");
            return $this->returnResponse(false,"wrong format \n");


        }
        return $this->returnResponse(true, [$nunOperations, $operations]);

    }

    /**
     * @param $nunOperations
     * @param $operations
     * @return bool
     */
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

    /**
     * @param $string
     * @return bool
     */
    function hasUnallowableChar($string): bool
    {

        $m = new Math;


        $number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // What is a number
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

    private function saveEquationsTODaTODO($res,$string){

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
    }

    // if we call the php class direct from cmd
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


}

