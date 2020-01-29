<?php

namespace App\Controller;
use App\Repository\GenreMovieRepository;
use App\Entity\GenreMovie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site")
     */
    public function index(GenreMovieRepository $genreMovieRepo)
    {
        $genresMovie = $genreMovieRepo->findall();
        return $this->render('site/index.html.twig', [
            'genresMovie' => $genresMovie,

        ]);
    }
}
