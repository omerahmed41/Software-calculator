<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EquationLog;
use App\Entity\OperationsLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
/**
 *  @Route("/Documentation/v1")
 */
class DocumentationController  extends BaseController
{



    /**
     * @Route ("/", methods={"GET"})
     * @param Request $request
     */
    function indexAction(Request $request)
    {

        return $this->render("/getting_start.html.twig", [
        ]);
    }

    /**
     * @Route ("/API", methods={"GET"})
     * @param Request $request
     */
    function apiAction(Request $request)
    {

        return $this->render("/API.html.twig", [
        ]);
    }

    /**
     * @Route ("/CLI", methods={"GET"})
     * @param Request $request
     */
    function cliAction(Request $request)
    {

        return $this->render("/CLI.html.twig", [
        ]);
    }

    /**
     * @Route ("/Install", methods={"GET"})
     * @param Request $request
     */
    function installAction(Request $request)
    {

        return $this->render("/Install.html.twig", [
        ]);
    }

    /**
     * @Route("/download-postmanColloction", name="download_postmanColloction")
     **/
    public function downloadAction(){
        $projectRoot = $this->appKernel->getProjectDir();
        $file = new File($projectRoot.'/src/downloads/Software_calculater.postman_collection.json');

        return $this->file($file);
    }
}