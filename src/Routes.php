<?php

use App\Controllers\UserController;
use App\Controllers\TodoController;
use App\Dto\CreateUserDto;
use App\Dto\TodoDto;
use App\Dto\TodoRequestDto;
use App\Dto\UserDto;
use App\Middlewares\BasicAuthMiddleware;
use TrishulApi\Core\Http\RequestType;
use TrishulApi\Core\Http\Router;

 $routes = [

    Router::parent("/users", [
        Router::children(RequestType::GET, "/", UserController::class."@getAllUsers", response_type:[UserDto::class], security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::GET, "/{userId}", UserController::class."@getUserById",security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::GET, "/1/me", UserController::class."@getLoggedInUser",security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::POST, "/", UserController::class."@saveUser",requestBody:[CreateUserDto::class]),
        Router::children(RequestType::PUT, "/{userId}", UserController::class."@updateUser",security:["basicAuth", "bearerAuth",], requestBody:UserDto::class),
        Router::children(RequestType::DELETE, "/{userId}", UserController::class."@deleteUser",security:["basicAuth", "bearerAuth",]),
    ],middlewares:[BasicAuthMiddleware::class], except:["/users"=>RequestType::POST])
    ];

    Router::parent("/todos", [
        Router::children(RequestType::GET, "/", TodoController::class."@getAllTodos", response_type:[TodoDto::class], security: ["basicAuth", "bearerAuth",]),
        Router::children(RequestType::GET, "/{todoId}", TodoController::class."@getTodoById",security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::POST, "/", TodoController::class."@createTodo", requestBody:[TodoRequestDto::class],security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::PUT, "/{todoId}", TodoController::class."@updateTodo", requestBody:[TodoDto::class],security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::DELETE, "/{todoId}", TodoController::class."@deleteTodo",security:["basicAuth", "bearerAuth",]),
        Router::children(RequestType::GET, "/user/{userId}", TodoController::class."@getTodosByUserId", response_type:[TodoDto::class],security:["basicAuth", "bearerAuth",]),
    ]);