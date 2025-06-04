# Todo App API

A lightweight RESTful API for managing users and todo items, built with the Trishul PHP framework.

## Features

- User registration, login, update, and deletion
- Basic authentication middleware
- CRUD operations for todos
- Per-user todo access and ownership checks
- OpenAPI (Swagger) documentation generation
- Modular structure with controllers, services, DTOs, and models

## Project Structure

```
.
├── .env
├── .gitignore
├── .htaccess
├── composer.json
├── composer.lock
├── index.php
├── src/
│   ├── Controllers/
│   ├── Dto/
│   ├── Middlewares/
│   ├── Models/
│   ├── Services/
│   ├── logs/
│   ├── Routes.php
│   └── Utils.php
└── vendor/
```

## Getting Started

### Prerequisites

- PHP 8.0+
- Composer
- MySQL (or compatible DB)

### Installation

1. **Clone the repository:**
   ```sh
   git clone <your-repo-url>
   cd todo_app
   ```

2. **Install dependencies:**
   ```sh
   composer install
   ```

3. **Configure environment:**
   - Copy `.env` and set your database credentials and app settings.

4. **Set up the database:**
   - Create a MySQL database and tables as required by the models (`users`, `todo_list`).

5. **Run the application:**
   ```sh
   php -S localhost:8080
   ```
   Or configure your Apache/Nginx server as needed.

### API Documentation

- Visit `/docs` endpoint for Swagger UI (OpenAPI documentation).

## Usage

### Authentication

- All protected endpoints require HTTP Basic Authentication.
- Use the `Authorization: Basic <base64(username:password)>` header.

### Example Endpoints

- `POST /users` - Register a new user
- `POST /login` - Login and get user info
- `GET /users/{userId}` - Get user by ID
- `PUT /users/{userId}` - Update user
- `DELETE /users/{userId}` - Delete user
- `GET /todos` - List todos for logged-in user
- `POST /todos` - Create a new todo
- `GET /todos/{todoId}` - Get a todo by ID
- `PUT /todos/{todoId}` - Update a todo
- `DELETE /todos/{todoId}` - Delete a todo

## Development

- Add new routes in [`src/Routes.php`](src/Routes.php)
- Implement business logic in [`src/Services/`](src/Services/)
- Add/modify models in [`src/Models/`](src/Models/)
- Use DTOs in [`src/Dto/`](src/Dto/) for request/response validation

## License

Apache-2.0

---

Made with [Trishul PHP Framework](https://github.com/trishulapi/framework)