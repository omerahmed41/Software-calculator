<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RESTAPI extends BaseController
{


    /**
     * @Route("/rest/v1/calculate", name="calculate",  methods={"POST"})
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

    /**
     * @Route ("/rest/v1/reports/getEquations")
     * @param Request $request
     * @return JsonResponse
     */
    function getEquations(Request $request): JsonResponse
    {
        $frequency = $request->request->get('frequency', 'monthly');


        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(EquationLog::class)->findAll();

        $serialized = $this->serializer->serialize($result, "json");

        return JsonResponse::fromJsonString($serialized);
    }
}