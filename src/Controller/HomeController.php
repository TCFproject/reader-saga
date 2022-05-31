<?php

namespace App\Controller;

use App\Entity\BD;
use App\Form\AuteurType;
use App\Repository\BDRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session, BDRepository $BDRepository): Response {
        return $this->render('home/index.html.twig', [
            'controller_name' => $session->get('Nom'),
            'bds' => $BDRepository->findAll()
        ]);
    }

    /**
     * @Route("/{titre}", name="detail")
     */
    public function detail(string $titre, BDRepository $BDRepository): Response{
        $dir = $this->getParameter('cover_directory').'\\'.$titre;
        $finder = Finder::create()
            ->in($dir)
            ->directories();
        $files = iterator_to_array($finder,false);
        return $this->render('home/detail.html.twig',[
            'bd' => $BDRepository->findOneBy(['titre'=>$titre]),
            'chapitre' => $files
        ]);
    }

    /**
     * @Route("/{titre}/{chapitre}", name="lire")
     */
    public function lecture(string $titre, string $chapitre):Response {
        $form = $this->createForm(AuteurType::class);
        $dir = $this->getParameter('cover_directory').'\\'.$titre.'\\'.$chapitre;
        $finder = Finder::create()
            ->in($dir)
            ->files();
        $files = iterator_to_array($finder,false);
        return $this->render('home/chapitre.html.twig',[
            'chapitre' => $chapitre,
            'titre' => $titre,
            'fileJPG' => $files,
            'form' => $form->createView()
        ]);
    }
}
