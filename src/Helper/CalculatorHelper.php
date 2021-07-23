<?php


namespace App\Helper;


use PhpParser\Builder\Class_;
use Psr\Log\LoggerInterface;

class CalculatorHelper
{

    private Logger $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = new Logger($logger);
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


      return  $this->runCalculation($parsedInput['message']);

    }

    function runCalculation($parsedInput): array
    {

        [$nunOperations, $operations] = $parsedInput;

        $calculateFunctions = $this->calculateFunctions($nunOperations);
        if (!$calculateFunctions['state']) {
            $this->logger->print_message($calculateFunctions['message'], 'error');
            return $this->returnResponse(false, $calculateFunctions['message']);

        }

        $nums = $calculateFunctions['result'];

        $calculateOperations = $this->calculateOperations($nums, $operations);
        if (!$calculateOperations['state']) {
            $this->logger->print_message($calculateOperations['message'], 'error');
            return $this->returnResponse(false, $calculateOperations['message']);

        }
        $result = $calculateOperations['result'];
        $this->logger->print_message("Result: $result", 'success');
        return  $this->returnResponse(true, $result);
    }

    function calculateOperations($nums, $operations): array
    {
        $m = new Math;


        // do * and / first
        foreach ($operations as $key => $opt) {
            if ($opt == '*' || $opt == "/") {
                $this->logger->print_message($nums[$key - 1]. $opt.  $nums[$key] ."= ");

                $calc = $m->calc($opt, [$nums[$key - 1], $nums[$key]]);
                if (!$calc['state']) {
                    $this->logger->print_message($calc['message'], 'error');
                    return $calc;
                }
                $result = $calc['result'];
                $this->logger->print_message($result ."\n");

                // array_shift($nums);
                unset($operations[$key]);

                $nums[$key] = $result;
                unset($nums[$key - 1]);

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
            $this->logger->print_message("$result $opt $nums[0] =");

            $calc = $m->calc($opt, [$result, $nums[0]]);
            if (!$calc['state']) {
                $this->logger->print_message($calc['message'], 'error');
                return $calc;
            }
            $result = $calc['result'];
            $this->logger->print_message($result. "\n");

            // array_shift($nums);
            array_shift($nums);

        }
        return $this->returnResponse(true, null, $result);
    }

    function calculateFunctions($nunOperations): array
    {
        $m = new Math;

        foreach ($nunOperations as $key => $nunOperation) {
            preg_match("/(round|omer|factorial)/", $nunOperation, $funName, PREG_OFFSET_CAPTURE);
            if (!$funName) {
                continue;
            }

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

        }
        $this->logger->print_message("numbers array:");
        $this->logger->print_message($nunOperations);

        return [
            'state' => true,
            'result' => $nunOperations
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

        $number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // What is a number
//    $functions = '(?:sinh?|cosh?|tanh?|abs|acosh?|asinh?|atanh?|exp|log10|deg2rad|rad2deg|sqrt|ceil|floor|round)'
//        .'\s*\((?1)+\)|\((?1)+\))(?:'; // Allowed PHP functions
        $functions = '(round|omer|factorial)'
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

