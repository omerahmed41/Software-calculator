<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
class RESTAPI  extends AbstractController
{

    /**
     * @Route("/calculate", name="calculate",  methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    function index (Request $request,LoggerInterface $logger): JsonResponse
    {
        $input = $request->request->get('input', 'No value');

        $cal = new CalculatorHelper();
//        $cal->startCLILogger();
      $result =   $cal->handleInput($input);
      return new JsonResponse($result);

//        $logger->info('I just got the logger');
//        $logger->error('An error occurred');
//
//        $logger->critical('I left the oven on!', [
//            // include extra "context" info in your logs
//            'cause' => 'in_hurry',
//        ]);
//        $routeName = $request->attributes->get('_route');
//        $input = urlencode("1+2+3");
//        $encoded = urldecode($input);
        $logger->info("Calculate input: $input");
        $entityManager = $this->getDoctrine()->getManager();

        $equation = new EquationLog();
        $equation->setEquation($input);
        $entityManager->persist($equation);


//        $equation->addOperation("+");

        // actually executes the queries (i.e. the INSERT query)
//        $entityManager->flush();
        return new JsonResponse(
            [
//                'Name' => 'omer',
                'input' => $input,
//                'encoded' => $encoded,
                'equation' => $equation->getEquation(),

            ]
        );
    }
}