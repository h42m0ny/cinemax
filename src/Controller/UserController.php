<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Movie;
use App\Entity\User;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     *  @Route("/movie/favourite/{imDBID}/add", name="add_favourite_movie", methods={"POST"}, options={"expose"=true})
     *  @IsGranted("ROLE_USER")
     */
    public function addFavouriteMovie(Movie $movie):JsonResponse {
        $message = '';
        /**
         * @var User $user
         */
        $user = $this->getUser(); //fonction native de Symfony qui sert à retrouver un user

        if (!$user->getMovies()->contains($movie)) {
            $user->addMovie($movie);
            $message = 'added';
        } else {
            $user->removeMovie($movie);
            $message = 'removed';
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($movie);
        $em->flush();

        return new JsonResponse(['message'=> $message,
        'data' => $movie->getTitle()]);
      }
}
