<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController
{

    protected $serializer;

    public function __construct() {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [
            new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => "y/m/d"]),
            new ObjectNormalizer()
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

}