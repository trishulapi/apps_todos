<?php

namespace App\Dto;

use App\Models\User;

class TodoDto
{
    public string $todo_id;
    public string $title;
    public $created_on;
    public int $created_by; // Foreign key to associate with a user

    public function __construct(
        string $todo_id,
        string $title,
        int $created_by
    ) {
        $this->todo_id = $todo_id;
        $this->title = $title;
        $this->created_by = $created_by;
    }
}