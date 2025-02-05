
# Ticket Management Backend

A backend API for managing tickets built with Laravel, featuring MongoDB support and Docker for easy development and deployment.

---

## Getting Started

Follow these steps to set up the development environment.

### 1. Clone the Repository

Run the following commands in your terminal:

```bash
git clone https://github.com/detailswes/ticket-management-backend.git
cd ticket-management-backend
```

---

### 2. Install Docker and Docker Compose

Make sure Docker and Docker Compose are installed on your machine.

- **[Download Docker](https://www.docker.com/get-started)**

---

### 3. Configure Environment Variables

1. Create a `.env` file in the project root directory.
2. Add the following variables and set them according to your environment:

```env
DB_CONNECTION=
DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
MONGODB_URI=
MONGODB_DATABASE=
```

Ensure these values are accurate to avoid setup issues.

---

### 4. Build and Start Docker Containers

Run the following command from the project root to build and start the containers:

```bash
docker-compose up -d --build
```

---

### 5. Verify Containers Are Running

Run this command to check the status of your Docker containers:

```bash
docker-compose ps
```

---

### 6. Run Commands Inside the Docker Container

1. Access the application container:

```bash
docker exec -it mini-ticket-project-app /bin/bash
```

2. Inside the container, execute the following commands:

```bash
composer install
composer require mongodb/laravel-mongodb
php artisan migrate
php artisan db:seed
```

---

## Additional Notes

- Make sure MongoDB is correctly set up and connected.
- Check the Laravel application logs if you encounter any issues.
- For further support, refer to the official documentation for [Laravel](https://laravel.com/docs) and [Docker](https://docs.docker.com/).

---
