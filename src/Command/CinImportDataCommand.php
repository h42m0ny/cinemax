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
use App\Entity\Movie; 
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
     * @var ContainerInterface
     */
    private $container;

    protected static $defaultName = 'cin:import-data';

    public function __construct(MovieRepository $movieRepo, ContainerInterface $container ) {
        $this->movieRepo = $movieRepo;
        $this->container = $container;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('This command gets infos from imdb api')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
       
        
        $this->importMoviesData();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }

    private function importMoviesData(){
        $httpClient = HttpClient::create();
   
     $api_url='https://api.themoviedb.org/3/discover/movie?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr-FR&sort_by=popularity.desc&include_adult=false&include_video=false&page=';
     $nbPage = 1;
     while ($nbPage <= 500) {
        $responseContent = json_decode($httpClient->request('GET', $api_url.$nbPage)->getContent());

    /**
     * @var EntityManager $movieRepo
     */
    $em = $this->container->get('doctrine')->getManager();

    foreach ($responseContent->results as $res) {
        if (!$this->movieRepo->findOneBy(['imDBID' => $res->id])) {
            $movie = new Movie();
            if (property_exists($res,'release_date')){
                $movie->setReleaseDate(new \DateTime($res->release_date));
               
            }
                $movie->setTitle($res->title);
                $movie->setNote($res->vote_count);
                $movie->setImDBID($res->id);
                $movie->setSummary($res->overview);
                if (property_exists($res,'poster_path')){

                }
                $movie->setImage($res->poster_path);
            $em->persist($movie);
        }
    }

    $nbPage++;
    
    }
    $em->flush();


    }

    private function importMovieGenres(){
        $api_url='https://api.themoviedb.org/3/genre/movie/list?api_key=1124f4b0c0c458a06050a35f0347130e&language=fr_FR';
    }
}
