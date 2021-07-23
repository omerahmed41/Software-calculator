<?php

namespace App\Helper;

class  Math
{
    private $x = 0;
    private $y = 0;

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
        'factorial' => [
            'arguments' => '1',
            'equation' => ' $factorial = 1;
                            for ($i = 1; $i <= $augments[0]; $i++){
                              $factorial = $augments[0] * $i;
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