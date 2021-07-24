<?php

namespace App\Helper;

class  Math
{
    private $x = 0;
    private $y = 0;

    public string $allowedFunctions = "(round|omer|square|cubic|power|factorial)";
    public array $operationsMatrix = [
        '+' => [
            'arguments' => '2',
            'equation' => 'return $augments[0] + $augments[1];',
        ],
        '-' => [
            'arguments' => '2',
            'equation' => 'return $augments[0] - $augments[1];',
        ],
        '*' => [
            'arguments' => '2',
            'equation' => 'return $augments[0] * $augments[1];',
        ],
        '/' => [
            'arguments' => '2',
            'equation' => 'return $augments[0] / $augments[1];',
        ],
        'omer' => [
            'arguments' => '2',
            'equation' => 'return ($augments[0]*2) + $augments[1];',
        ],
        'square' => [
            'arguments' => '1',
            'equation' => 'return  $augments[0]*$augments[0];',
        ],
        'cubic' => [
            'arguments' => '1',
            'equation' => 'return  $augments[0]*$augments[0]*$augments[0];',
        ],
        'power' => [
            'arguments' => '2',
            'equation' => 'return  pow( $augments[0],$augments[1]);',
        ],

        'factorial' => [
            'arguments' => '1',
            'equation' => ' $factorial = 1;
                            for ($i = 1; $i <= $augments[0]; $i++){
                              $factorial = $factorial * $i;
                            }
                            return $factorial;',
        ],

    ];

    public array $functionsMatrix = [
        'omer' => 'return $2*x + 2*$y;',
    ];

    /**
     * @param string $opt
     * @param $augments
     * @return mixed
     */
    public function calc(string $opt, $augments)
    {
        if (!array_key_exists($opt,$this->operationsMatrix)){
            return [
                'state' => false,
                'message' => "$opt not in our Database"
            ];
        }
        if ($this->operationsMatrix[$opt]['arguments'] != count($augments)) {
            return [
                'state' => false,
                'message' => "wrong argument for $opt"
            ];
        }

        $result = eval($this->operationsMatrix[$opt]['equation']);
        if ($result) {
            return [
                'state' => true,
                'result' => $result
            ];
        }
    }

}