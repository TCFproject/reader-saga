<?php

namespace App\Controller;

use App\Entity\BD;
use App\Form\BDType;
use App\Repository\AuteurRepository;
use App\Repository\BDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Migrations\Configuration\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/b/d")
 */
class BDController extends AbstractController
{
    /**
     * @Route("/", name="b_d_index", methods={"GET"})
     */
    public function index(BDRepository $bDRepository, Filesystem $filesystem): Response
    {
        var_dump( $filesystem->readlink("/repo_bd"));
        return $this->render('bd/index.html.twig', [
            'b_ds' => $bDRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="b_d_new", methods={"GET","POST"})
     */
    public function new(Request $request, AuteurRepository $auteurRepository, SessionInterface $session): Response
    {
        $bD = new BD();
        $renseign_auteur = $auteurRepository->findOneBy([
            'nom' => $session->get('Nom'),
            'prenom' => $session->get('Prenom'),
            'pseudo' => $session->get('Pseudo')
        ]);
        $bD->setAuteur($renseign_auteur);
        $form = $this->createForm(BDType::class, $bD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bD);
            $entityManager->flush();

            return $this->redirectToRoute('b_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bd/new.html.twig', [
            'b_d' => $bD,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="b_d_show", methods={"GET"})
     */
    public function show(BD $bD): Response
    {
        return $this->render('bd/show.html.twig', [
            'b_d' => $bD,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="b_d_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BD $bD): Response
    {
        $form = $this->createForm(BDType::class, $bD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('b_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bd/edit.html.twig', [
            'b_d' => $bD,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="b_d_delete", methods={"POST"})
     */
    public function delete(Request $request, BD $bD): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bD->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bD);
            $entityManager->flush();
        }

        return $this->redirectToRoute('b_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
