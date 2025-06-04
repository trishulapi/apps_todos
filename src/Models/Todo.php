<?php

namespace App\Models;

use TrishulApi\Core\Data\Model;
class Todo extends Model
{
    public static string $table_name = 'todo_list';
    public static string $primary_key = 'todo_id';

    
}