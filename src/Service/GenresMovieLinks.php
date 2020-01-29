<?php

namespace App\Service;

use App\Repository\GenreMovieRepository;

class GenresMovieLinks {
    /**
    * @var GenreMovieRepository $genreRepo
    */
    private $genreRepo;

    public function __construct(GenreMovieRepository $genreRepo) {
        $this->genreRepo = $genreRepo;
    }


    public function getGenres(){
        return $this->genreRepo->findAll();
    }

}