<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @Route("/rest/v1")
 */
class RESTAPI extends BaseController
{


    /**
     * @Route("/calculate", name="calculate",  methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    function index(Request $request, LoggerInterface $logger): JsonResponse
    {
        $input = $request->request->get('input', 'No value');
        $logger->info("Start Calculations for $input");

        $em = $this->getDoctrine()->getManager();
        $cal = new CalculatorHelper($logger,$em);
//        $cal->startCLILogger();
        $result = $cal->handleInput($input);

        $logger->info("$input result is: " . json_encode($result));

        return new JsonResponse($result);


    }


}