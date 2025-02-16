# News Aggregator API

This is a **News Aggregator API** built with Laravel. The application fetches and aggregates news articles from various sources and allows users to manage preferences, search for articles, and interact with them via an API.

## 🚀 Features
- **User Authentication** (Register, Login, Logout) using Laravel Sanctum
- **Article Management** (Fetching, Searching, and Viewing articles)
- **User Preferences** (Categories, Sources, Authors)
- **News Aggregation** from external APIs via scheduled jobs
- **API Documentation** using Swagger (OpenAPI)
- **Dockerized Deployment** with MySQL and Redis
- **Unit & Feature Testing** with PHPUnit

## 🛠️ Prerequisites
Before setting up the project, make sure you have:
- **Docker & Docker Compose** installed
- **Git** installed
- **Composer** installed

## 🔧 Setup Instructions
### Step 1: Clone the Repository
```sh
git clone https://github.com/your-username/news-aggregator.git
cd news-aggregator
```

### Step 2: Set Up Environment Variables
Copy the `.env.example` file and update necessary configurations:
```sh
cp .env.example .env
```
Make sure to update database credentials:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=news_aggregator
DB_USERNAME=root
DB_PASSWORD=root
```

### Step 3: Start Docker Containers
```sh
docker-compose up -d --build
```
This will start:
- **Laravel App** (running on `localhost:8080`)
- **MySQL Database** (running on `localhost:3306`)
- **Redis Cache**

### Step 4: Install Dependencies
```sh
docker exec -it news-aggregator-app composer install --no-dev --optimize-autoloader
```

### Step 5: Run Migrations & Seed Database
```sh
docker exec -it news-aggregator-app php artisan migrate --seed
```

### Step 6: Generate API Documentation
```sh
docker exec -it news-aggregator-app php artisan l5-swagger:generate
```
Now, you can access the API documentation at:
- **http://localhost:8080/api/documentation**

### Step 7: Fetch News Articles
To fetch and store news articles, run:
```sh
docker exec -it news-aggregator-app php artisan news:fetch
```

### Step 8: Running Tests
To execute all test cases, run:
```sh
docker exec -it news-aggregator-app php artisan test
```

## 🖥️ API Endpoints
### Authentication
| Method | Endpoint      | Description       |
|--------|-------------|------------------|
| POST   | `/api/register` | Register a new user |
| POST   | `/api/login` | Login a user |
| POST   | `/api/logout` | Logout a user |

### Articles
| Method | Endpoint | Description |
|--------|---------|-------------|
| GET    | `/api/articles` | Fetch all articles |
| GET    | `/api/articles/{id}` | Fetch a single article |
| GET    | `/api/articles/search?keyword=abc` | Search articles |

### User Preferences
| Method | Endpoint | Description |
|--------|---------|-------------|
| GET    | `/api/preferences` | Get user preferences |
| POST   | `/api/preferences` | Save preferences |
| PUT    | `/api/preferences` | Update preferences |

## 🛠️ Additional Commands
### Restart Docker Containers
```sh
docker-compose restart
```
### Stop Docker Containers
```sh
docker-compose down
```

## 🎯 Conclusion
This **News Aggregator API** allows users to fetch and manage news articles from various sources efficiently. Feel free to contribute and improve the project!

Happy Coding! 🚀

