<?php

namespace App\Command;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\GenreMovieRepository;
use App\Repository\GenreSerieRepository;
use App\Entity\Movie;
use App\Entity\GenreMovie;
use App\Entity\Serie;
use App\Entity\GenreSerie;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\Bundle\DoctrineBundle\Mapping\ContainerEntityListenerResolver;
use Doctrine\ORM\EntityManager;


class CinImportDataCommand extends Command
{
    /**
     * @var MovieRepository
     */
    private $movieRepo;

    /**
     * @var GenreSerieRepository
     */
    private $genreSerieRepo;

    /**
     * @var SerieRepository
     */
    private $serieRepo;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    protected static $defaultName = 'cin:import-data';

    public function __construct(MovieRepository $movieRepo, GenreMovieRepository $genreMovieRepo, SerieRepository $serieRepo, GenreSerieRepository $genreSerieRepo, ContainerInterface $container)
    {
        $this->movieRepo = $movieRepo;
        $this->genreMovieRepo = $genreMovieRepo;
        $this->genreSerieRepo = $genreSerieRepo;
        $this->serieRepo = $serieRepo;
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('This command gets infos from imdb api')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->importMovieGenresData();
        $this->importSeriesGenreData();
        $this->importMoviesData();
        $this->importSeriesData();

        $io->success('Command succeed');

        return 0;
    }

    private function importMoviesData()
    {
        $httpClient = HttpClient::create();

        $api_url = 'https://api.themoviedb.org/3/discover/movie?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr-FR&sort_by=popularity.desc&include_adult=false&include_video=false&page=';
        $nbPage = 1;

        $responseContent = json_decode($httpClient->request('GET', $api_url . $nbPage)->getContent());

        while ($nbPage <= $responseContent->total_pages) {
            foreach ($responseContent->results as $res) {
                $movie = $this->movieRepo->findOneBy(['imDBID' => $res->id]);
                if (!$movie) {
                    $movie = new Movie();
                    if (property_exists($res, 'release_date')) {
                        $movie->setReleaseDate(new \DateTime($res->release_date));
                    } else {
                        $movie->setReleaseDate(new \DateTime());
                    }
                    $movie->setTitle($res->title);
                    $movie->setNote($res->vote_average);
                    $movie->setImDBID($res->id);
                    $movie->setSummary($res->overview);
                    if (property_exists($res, 'poster_path')) {
                        $movie->setImage($res->poster_path);
                    }
                }
                $genres = $res->genre_ids;
                if (count($genres) > 0) {
                    foreach ($genres as $genreId) {
                        $genre = $this->genreMovieRepo->findOneBy(['imDBID' => $genreId]);
                        if ($genre) {
                            $movie->addGenre($genre);
                        }
                    }
                }
                $this->em->persist($movie);
            }
            $nbPage++;
        }
        $this->em->flush();
    }

    private function importMovieGenresData()
    {
        $api_url = 'https://api.themoviedb.org/3/genre/movie/list?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr_FR';

        $httpClient = HttpClient::create();

        $responseContent = json_decode($httpClient->request('GET', $api_url)->getContent());

        foreach ($responseContent->genres as $res) {
            if (!$this->genreMovieRepo->findOneBy(['imDBID' => $res->id])) {
                $genreMovie = new GenreMovie();
                $genreMovie->setImDBID($res->id);
                $genreMovie->setName($res->name);

                $this->em->persist($genreMovie);
            }
        }
        $this->em->flush();
    }

    private function importSeriesData()
    {
        $api_url = 'https://api.themoviedb.org/3/discover/tv?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr-FR&sort_by=popularity.desc&page=1&timezone=America%2FNew_York&include_null_first_air_dates=false&page=';

        $nbPage = 1;
        $httpClient = HttpClient::create();
        $responseContent = json_decode($httpClient->request('GET', $api_url)->getContent());

        $totalPages = $responseContent->total_pages;
        while ($nbPage <= $totalPages) {

            foreach ($responseContent->results as $res) {
                $serie = $this->serieRepo->findOneBy(['imDBID' => $res->id]);
                if (!$serie) {
                    $serie = new Serie();
                    $serie->setImDBID($res->id);
                    $serie->setName($res->name);
                    $serie->setNote($res->vote_average);
                    $serie->setSummary($res->overview);
                    $serie->setImage($res->poster_path);
                    if (property_exists($res, 'first_air_date')) {
                        $serie->setFirstAirDate(new \DateTime($res->first_air_date));
                    }
                }
                $genres = $res->genre_ids;
                if (count($genres) > 0) {
                    foreach ($genres as $genreId) {
                        $genre = $this->genreSerieRepo->findOneBy(['imDBID' => $genreId]);  
                        if ($genre) {
                            $serie->addGenreSeries($genre);
                        }
                    }
                }
                $this->em->persist($serie);
            }

            $nbPage++;
        }
        $this->em->flush();
    }

    public function importSeriesGenreData()
    {
        $api_url = 'https://api.themoviedb.org/3/genre/tv/list?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr-FR';

        $httpClient = HttpClient::create();

        $responseContent = json_decode($httpClient->request('GET', $api_url)->getContent());

        foreach ($responseContent->genres as $res) {
            if (!$this->genreMovieRepo->findOneBy(['imDBID' => $res->id])) {
                $genreSerie = new GenreSerie();
                $genreSerie->setImDBID($res->id);
                $genreSerie->setName($res->name);

                $this->em->persist($genreSerie);
            }
        }
        $this->em->flush();
    }
}
