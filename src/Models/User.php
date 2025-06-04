<?php

namespace App\Models;
use TrishulApi\Core\Data\Model;
class User extends Model
{
    public static string $table_name = 'users';
    public static string $primary_key = 'user_id';
    private string $name;
    private string $username;
    private string $password;

    public function __construct(string $name, string $username, string $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
    }
   

    
}