<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Template;

/**
 *  @Route("/")
 */
class ReportController extends BaseController
{


    /**
     * @Route ("/", methods={"GET"})
     * @param Request $request
     */
    function index(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $equations = $em->getRepository(EquationLog::class)->getTop(8);

        $em = $this->getDoctrine()->getManager();
        $operations = $em->getRepository(OperationsLog::class)->distinct();

//        return new JsonResponse($result);
//        $serialized = $this->serializer->serialize($result, "json");

        return $this->render("/index.html.twig", [
            'equations' => $equations,
            'operations' => $operations,
        ]);
    }
}