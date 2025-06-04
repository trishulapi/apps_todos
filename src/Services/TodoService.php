<?php

namespace App\Services;

use App\Models\Todo;
use App\Models\User;
use App\Utils;
use Exception;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\ResourceNotFoundException;
use TrishulApi\Core\Exception\UnauthorizedException;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\Response;

class TodoService
{
    private Request $request;
    // This class will handle the business logic for Todo operations.
    // It will interact with the Todo model and perform CRUD operations.
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
   
    public function getAllTodos():Response
    {
        return Response::json(HttpStatus::OK, Todo::all());
    }

    public function getTodoById($todoId)
    {
        
        $todo = Todo::find(conditions:["todo_id = $todoId"])[0];
        if (!$todo) {
            throw new ResourceNotFoundException("Todo not found for this id: ".$todoId);
        }
        return $todo;
    }

    public function createTodo(array $todo)
    {
        if (empty($todo['title']) || empty($todo['created_by'])) {
            return Response::json(HttpStatus::BAD_REQUEST, ['message' => 'Title and created_by are required']);
        }
        $id = Todo::create($todo);
        return Todo::find(["todo_id = $id"])[0];
    }

    public function updateTodo($todoId, $data)
    {
        
        Todo::update($todoId, [
            'title' => $data->title,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $data->created_by
        ]);
        
    }

    public function getTodosByUserId($userId)
    {
        if (!$userId) {
            throw new Exception("User Id is required.");
        }
        $todos = Todo::filter(["created_by = $userId"]);
        
        return $todos;
    }

    public function deleteTodo($todoId)
    {
       
        $todo = Todo::find(conditions:["todo_id = $todoId"])[0];
        if (!$todo) {
            throw new ResourceNotFoundException("Todo not found for this todo Id");
        }
        
        Todo::delete($todoId);
    }
}