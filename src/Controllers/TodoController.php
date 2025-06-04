<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Services\TodoService;
use App\Utils;
use Exception;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\UnauthorizedException;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\Response;

class TodoController
{

    private TodoService $todoService;
    private Request $request;
    public function __construct(TodoService $todoService, Request $request)
    {
        $this->todoService = $todoService;
        $this->request = $request;
    }

    public function getAllTodos()
    {
        $auth = $this->request->header()->get('Authorization');
        $basic_auth = explode('Basic ', $auth)[1];
        $user = Utils::get_user_from_basic_auth();
        if ($user == null) {
            throw new UnauthorizedException("Not Authorized");
        }

        return Response::json(HttpStatus::OK, $this->todoService->getTodosByUserId($user['user_id']));
    }


    public function createTodo()
    {
        $title = $this->request->body()->get('title');
        $user = Utils::get_user_from_basic_auth();
        if ($user == null) {
            throw new UnauthorizedException("Not Authorized");
        }
        $todo = ['title' => $title, 'created_by' => $user['user_id'], 'created_on' => date("Y-m-d h:i:s")];
        return Response::json(HttpStatus::OK, $this->todoService->createTodo($todo));
    }


    public function deleteTodo()
    {
        $todoId = $this->request->path()->get('todoId');
        if (empty($todoId)) {
            throw new Exception("Todo id is required.");
        }
        $user = Utils::get_user_from_basic_auth();
        if ($user == null) {
            throw new UnauthorizedException("Not authorized");
        }

        $todo = Todo::find(["todoId = $todoId"])[0];

        if ($user['user_id'] != $todo['created_by']) {
            throw new Exception("You are not the onwer of this todo.");
        }

        $this->todoService->deleteTodo($todoId);
        return Response::json(HttpStatus::OK, ["message" => "Deleted successfully."]);
    }


    public function getTodoById()
    {
        $todoId = $this->request->path()->get('todoId');
        if (empty($todoId)) {
            throw new Exception("Todo id is required.");
        }

        $user = Utils::get_user_from_basic_auth();
        if ($user == null) {
            throw new UnauthorizedException("Not authorized");
        }

        $todo = Todo::find(["todo_id = $todoId"])[0];

        if ($user['user_id'] != $todo['created_by']) {
            throw new Exception("You are not the onwer of this todo.");
        }

        return Response::json(HttpStatus::OK, $this->todoService->getTodoById($todoId));
    }


    public function updateTodo()
    {
        $todoId = $this->request->path()->get('todoId');
        if (empty($todoId)) {
            throw new Exception("Todo id is required.");
        }

        $user = Utils::get_user_from_basic_auth();
        if ($user == null) {
            throw new UnauthorizedException("Not authorized");
        }
        $todoObj = $this->request->body()->data();

        if ($todoObj == null) {
            throw new Exception("Required todo object in request body");
        }

        $todo = Todo::find(["todo_id = $todoId"])[0];

        if ($todo == null) {
            throw new Exception("Could not find todo for this id " . $todoId);
        }

        if ($user['user_id'] != $todo['created_by']) {
            throw new Exception("You are not the onwer of this todo.");
        }
        $this->todoService->updateTodo($todoId, $todoObj);

        return Response::json(HttpStatus::OK, ["message" => "Todo updated successfully"]);
    }


   
}
