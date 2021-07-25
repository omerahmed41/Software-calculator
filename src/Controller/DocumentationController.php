<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @Route("/Documentation/v1")
 */
class DocumentationController  extends BaseController
{

    /**
     * @Route ("/", methods={"GET"})
     * @param Request $request
     */
    function index(Request $request)
    {

        return $this->render("/getting_start.html.twig", [
        ]);
    }

    /**
     * @Route ("/API", methods={"GET"})
     * @param Request $request
     */
    function api(Request $request)
    {

        return $this->render("/API.html.twig", [
        ]);
    }

    /**
     * @Route ("/CLI", methods={"GET"})
     * @param Request $request
     */
    function cli(Request $request)
    {

        return $this->render("/CLI.html.twig", [
        ]);
    }

    /**
     * @Route ("/Install", methods={"GET"})
     * @param Request $request
     */
    function install(Request $request)
    {

        return $this->render("/Install.html.twig", [
        ]);
    }
}