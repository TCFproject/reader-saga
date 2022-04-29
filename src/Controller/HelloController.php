<?php


namespace App\Controller;


use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(AuteurRepository $auteurRepository){

        return $this->render('index.html.twig',[
            "auteurs" => $auteurRepository->findAll()
        ]);
    }

    /**
     * @Route("/params/{name}", name="params")
     */
    public function params($name){
        return new Response("Params ".$name." !!");
    }

}
?>
