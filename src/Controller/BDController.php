<?php

namespace App\Controller;

use App\Entity\BD;
use App\Form\AddChapterType;
use App\Form\BDType;
use App\Repository\AuteurRepository;
use App\Repository\BDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Migrations\Configuration\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        return $this->render('bd/index.html.twig', [
            'b_ds' => $bDRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="b_d_new", methods={"GET","POST"})
     */
    public function new(Filesystem $filesystem, Request $request, AuteurRepository $auteurRepository, SessionInterface $session): Response
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
            $coverFile = $form->get('FilePath')->getData();
            $path_in_database =$coverFile->getClientOriginalName();
            if ($coverFile){
                try{
                    $coverFile->move(
                        $this->getParameter('cover_directory'),
                        $path_in_database
                    );
                    $filesystem->mkdir('comics\\'.$form->get('titre')->getData());
                }catch (FileException $e){
                    $e->getMessage();
                }
                $bD->setFilePath($path_in_database);
            }
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
     * @Route("/{id}", name="b_d_show", methods={"GET","POST"})
     */
    public function show(BD $bD, Request $request, Filesystem $filesystem, SluggerInterface $slugger): Response {
        $dir = $this->getParameter('cover_directory').'\\'.$bD->getTitre();
        $finder = Finder::create()
            ->in($dir)
            ->directories();
        $files = iterator_to_array($finder,false);

        $form = $this->createForm(AddChapterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $filePath = 'comics\\'.$bD->getTitre().'\\'.$form->get("Nom_du_chapitre")->getData();
            $filesystem->mkdir($filePath);
            $imgs = $request->files->get("add_chapter");
            foreach ($imgs["Selectionnez_un_chapitre"] as $img){
                try{
                    $img->move(
                        $filePath,
                        $img->getClientOriginalName()
                    );
                }catch (FileException $e){
                    $e->getMessage();
                }
            }
        }
        return $this->render('bd/show.html.twig', [
            'b_d' => $bD,
            'form' => $form->createView(),
            'chapters' => $files
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
    public function delete(Request $request, BD $bD, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bD->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bD);
            $entityManager->flush();
        }

        try{
            $filesystem->remove("comics/".$bD->getTitre());
            $filesystem->remove("comics/".$bD->getFilePath());
        }catch (FileException $e){
            $e->getMessage();
        }
        return $this->redirectToRoute('b_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
