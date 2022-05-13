<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\Auteur1Type;
use App\Form\ConnType;
use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auteur")
 */
class AuteurController extends AbstractController
{
    /**
     * @Route("/", name="auteur_index", methods={"GET"})
     */
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/connect", name="auteur_connect", methods={"GET","POST"})
     */
    public function connect(Request $request,AuteurRepository $auteurRepository, SessionInterface $session):Response{
        $auteur = new Auteur();
        $form = $this->createForm(Auteur1Type::class, $auteur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        $formConnect = $this->createForm(ConnType::class);
        $formConnect->handleRequest($request);
        if ($formConnect->isSubmitted() && $formConnect->isValid()){
            $unAuteur = $auteurRepository->findOneBy([
                'email'=>$formConnect->get('Email')->getData(),
                'mdp'=>$formConnect->get('Mot_de_passe')->getData()
            ]);
            if ($unAuteur){
                $session->set('Nom', $unAuteur->getNom());
                $session->set('Prenom', $unAuteur->getPrenom());
                $session->set('Pseudo', $unAuteur->getPseudo());
                return $this->redirectToRoute('home',[],Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('auteur/connect.html.twig',[
            'form' => $form->createView(),
            'formConnect' => $formConnect->createView()
        ]);
    }

    /**
     * @Route("/disconnect", name="auteur_disconnect", methods={"GET","POST"})
     */
    public function disconnect(SessionInterface $session):Response{
        $session->clear();
        return $this->redirectToRoute('home',[],Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/new", name="auteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(Auteur1Type::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur): Response
    {
        return $this->render('auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Auteur $auteur): Response
    {
        $form = $this->createForm(Auteur1Type::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Auteur $auteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
