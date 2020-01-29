## Create project

 Command to create empty web project : composer create-project symfony/website-skeleton project_name

 launch server : symfony server:start

 create entity : bin/console make:entity 

 create CRUD : bin/console make:crud 
 

 To create bdd :

IN .env file update line 
DATABAS_URL=mysql://root@127.0.0.1:3306/cinemax
launch  : bin/console doctrine:database:create 

 to create migration : php bin/console make:migration

 to launch the migration : php bin/console doctrine:migrations:migrate

 php bin/console make:entity --regenerate

To create a command: bin/console make:command 
(this creates a class in src/Command)


// tell Doctrine you want to (eventually) save the Product (no queries yet)
$entityManager->persist($entity);

// actually executes the queries (i.e. the INSERT query)
$entityManager->flush();


Bundle pour ajax 
composer require friendsofsymfony/jsrouting-bundle

php bin/console assets:install --symlink public