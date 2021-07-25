<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Template;

/**
 *  @Route("/")
 */
class MainController extends BaseController
{


    /**
     * @Route ("/", methods={"POST", "GET"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    function index(Request $request, LoggerInterface $logger)
    {
        $finput = $request->request->get('finput');

        $logger->info("Start Calculations for $finput");

        $em = $this->getDoctrine()->getManager();
        $cal = new CalculatorHelper($logger,$em);
//        $cal->startCLILogger();
        $result = $cal->handleInput($finput);

        $logger->info("$finput result is: " . json_encode($result));

        return $this->render("/index.html.twig", [
            'finput' => $finput,
            'state' => $result['state'],
            'message' => $result['message'],
            'result' => $result['result'],
        ]);
    }
}