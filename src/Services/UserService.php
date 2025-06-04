<?php


namespace App\Services;

use App\Models\User;
use Exception;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\ResourceNotFoundException;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\Response;

class UserService
{

    public function __construct()
    {
    }   
    
    /**
     * Get user by ID.
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserById($userId)
    {
        // Logic to retrieve user by ID from the database
        // This is a placeholder implementation
        return User::find(conditions: ["user_id = " . $userId])[0];
    }

    /**
     * Create a new user.
     *
     * @param array $userData
     * @return bool
     */
    public function createUser($user): array
    {
        
       $user = ["name" => $user->name, "username" => $user->username, "password" => $user->password];
        $id = User::create($user);
        return User::find(conditions:["user_id = $id"])[0];
    }

    public function getAllUsers()
    {
        
        return User::all();
    }


    public function login($username, $password)
    {
       
        $user = User::find(conditions: ["username = '$username' AND password = '$password'"]);
        
        if (count($user) > 0) {
            return $user[0];
        } else {
            return false;
        }
    }

    public function deleteById($userId)
    {
        if($userId == null)
        {
            throw new Exception("userId is required.");
        }

        $user = User::find(["user_id = $userId"])[0];
        if($user == null)
        {
            throw new ResourceNotFoundException("User not found for this id: ".$userId);
        }

        User::delete($userId);
    }

    public function updateUser($userId, $data)
    {
        if(!isset($data->username) || !isset($data->name) || !isset($data->password))
        User::update($userId, [
            "username"=>$data->username,
            "name"=>$data->name,
            "password"=>$data->password
        ]);
    }
}