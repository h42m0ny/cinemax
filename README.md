#Netflop

This project is a student project which aims to learn to use the framework Symfony

It collects films and series infos from Tmdb apis. For now a user can register, connect and chose film as favourite.

####Step 1
Clone the project

####Step 2 
Rename .env.ORIG to .env and set up your specific env variables 
- DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

####Step 3
`composer install`

####Step 4
`npm install`

####Step 5
If symfony isn't installed on your pc you have to get it then run the command:
`symfony server:start`

####Step 6 
Create db and populate it <br/>
`bin/console doctrine:database:create` <br/>
`bin/console doctrine:migrations:migrate`

###To do : 
- get all episodes link to a serie
- make it possible to put a serie in favourite
        
