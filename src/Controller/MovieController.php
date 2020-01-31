<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType; 
use App\Entity\GenreMovie;
use App\Repository\GenreMovieRepository;
use App\Repository\MovieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/movie")
 * @IsGranted("ROLE_USER")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/{page}", name="movie_index", methods={"GET"}, options={"expose"=true}, defaults={"page"=1})
     */
    public function index(MovieRepository $movieRepository, GenreMovieRepository $genreRepo, PaginatorInterface $paginator, int $page): Response
    {
        $genresMovie = $genreRepo->findAll();
        $movies = $movieRepository->getMoviesPagination($paginator, $page);
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'genresMovie' => $genresMovie
        ]);
    }

    /**
     * @Route("/genre/{name}/{page}", name="movie_index_genre", methods={"GET"}, defaults={"page"=1})
     */
    public function indexByGenre(GenreMovieRepository $genreRepo, GenreMovie $genre, MovieRepository $movieRepo, PaginatorInterface $paginator, int $page){
        return $this->render('movie/index.html.twig',[
            'movies' => $movieRepo->getMoviesByGenre($paginator, $genre->getId(), $page),
            'genre' => $genre,
            'genresMovie' =>$genreRepo->findAll()
        ]);

    }

    /**
     * @Route("/new", name="movie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Movie $movie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_index');
    }
}
