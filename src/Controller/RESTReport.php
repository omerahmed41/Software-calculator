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
 *  @Route("/rest/v1/reports")
 */
class RESTReport extends BaseController
{


    /**
     * @Route ("/getTopEquations", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    function getTopEquations(Request $request): JsonResponse
    {
        $value = $request->get('top', '10');

        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(EquationLog::class)->getTop($value);

        $serialized = $this->serializer->serialize($result, "json");

        return JsonResponse::fromJsonString($serialized);
    }

    /**
     * @Route ("/operationSummary", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    function operationSummary(Request $request): JsonResponse
    {
        $frequency = $request->get('frequency', 'week');


        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(OperationsLog::class)->distinct($frequency);

//        return new JsonResponse($result);
        $serialized = $this->serializer->serialize($result, "json");

        return JsonResponse::fromJsonString($serialized);
    }
}