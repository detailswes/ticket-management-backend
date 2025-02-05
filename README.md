Laravel (mini-ticket-system)


This project is a Laravel-based API that allows CRUD operations for tickets, with MongoDB as the database.

1.Prerequisites
    Make sure you have Docker and Docker Compose installed on your machine.


Setup Instructions
==================


2.Start the Docker Containers
    To set up the Docker environment, navigate to the project folder and run:
    docker-compose up --build


3.Start the Docker Containers (If Already Built)
    If you've already built the containers before and just want to start them, run:
    docker-compose up -d


4.Check Running Containers
    To verify that the containers are running, you can use the following command:
    docker ps

5.Access the Project
    The application will be accessible at: 
    http://localhost:8000


6.Use the API
    To interact with the tickets API, use the following endpoint:
    http://0.0.0.0:8080/api/tickets