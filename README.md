# Football Project

# Installation

1 -Clone

 git clone https://github.com/SyanCS/football_project.git

2 - Build Docker

 cd football_project 
 
docker-compose up -d --build

3 - Setup Backend

docker exec -it football-team-management-app composer install

docker exec -it football-team-management-app php bin/console doctrine:database:create

docker exec -it football-team-management-app php bin/console doctrine:migrations:migrate

docker exec -it football-team-management-app php bin/console doctrine:fixtures:load


5 - Setup test

docker exec -it football-team-management-app php bin/console doctrine:database:create --env=test

docker exec -it football-team-management-app php bin/console doctrine:migrations:migrate -n --env=test

docker exec -it football-team-management-app php bin/console doctrine:fixtures:load --env=test

docker exec -it football-team-management-app php bin/console doctrine:fixtures:load --env=test

docker exec -it football-team-management-app php bin/phpunit tests/Controller


6 - Now you can access 

http://localhost:8080/api/v1/teams

http://localhost:8080/api/v1/players

http://localhost:8080/api/v1/teams/{id}/players

http://localhost:8080/api/v1/transfers


7 - Now the frontend

cd ./frontend

npm install

npm run serve


8 - Now you can access  http://localhost:8081/


OBS: Make sure there are no other apps running on ports 8080 and 8081



# Notes

- I've done a version of the backend using database persistence just for fun, the correct one is the backend_json_server

