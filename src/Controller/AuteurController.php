<?php


namespace App\Controller;


use App\Entity\Auteur;
use App\Form\AuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuteurController extends AbstractController
{
    /**
     * @Route("/newAuteur", name="nouvel_auteur")
     * @param Request $request
     * @return Response
     */
    public function newAuteur(Request $request): Response{
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();
        }
        return $this->render("auteur/newAuteur.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
