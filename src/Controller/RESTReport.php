<?php


namespace App\Controller;

use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use App\Helper\CalculatorHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * @Route ("/exportSummary", methods={"GET"})
     * @param Request $request
     * @return false|int|BinaryFileResponse
     */
    function exportSummary(Request $request)
    {
        $frequency = $request->get('frequency', 'week');
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(OperationsLog::class)->distinct($frequency);

      return  $this->getCSVFile($result,"Summary");

    }

    /**
     * @Route ("/exportLastEquations", methods={"GET"})
     * @param Request $request
     * @return BinaryFileResponse
     */
    function exportLastEquations(Request $request): BinaryFileResponse
    {
        $value = $request->get('top', '10');

        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(EquationLog::class)->getTop($value);
      return  $this->getCSVFile($result,"LastEquations");

    }


    /**
     * @param $data
     * @param $fileName
     * @return BinaryFileResponse
     */
    private function getCSVFile($data, $fileName){
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        file_put_contents(
            "$fileName.csv",
            $serializer->encode($data, 'csv')
        );
        $projectRoot = $this->appKernel->getProjectDir();
        $file = new File($projectRoot."/public/$fileName.csv");
        return $this->file($file);
    }
}