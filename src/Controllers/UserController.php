<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Utils;
use Exception;
use LDAP\Result;
use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Exception\UnauthorizedException;
use TrishulApi\Core\Http\Response;
use TrishulApi\Core\Http\Request;
class UserController
{

    private UserService $userService;
    private Request $request;
    public function __construct(UserService $userService, Request $request)
    {
        $this->userService = new UserService($request);
        $this->request = $request;
    }
    /**
     * Handle user login.
     *
     * @return Response
     */
    public function login(): Response
    {
        // Logic to handle user login
        $username = $this->request->body()->get('username');
        $password = $this->request->body()->get('password');
        if (empty($username) || empty($password)) {
            return Response::json(HttpStatus::BAD_REQUEST, ["message" => "Username and password are required"]);
        }
        $user = $this->userService->login($username, $password);
        if (!$user) {
            return Response::json(HttpStatus::UNAUTHORIZED, ["message" => "Invalid credentials"]);
        }
        // This is a placeholder implementation
        return Response::json(HttpStatus::OK, ["message" => "User logged in successfully", "user" => $user]);
    }


    public function saveUser(): Response
    {
        // Logic to save user data
        $userData = $this->request->body()->data();
        if (empty($userData)) {
            return Response::json(HttpStatus::BAD_REQUEST, ["message" => "User data is required"]);
        }
        $user = $this->userService->createUser($userData);
        if (!$user) {
            return Response::json(HttpStatus::INTERNAL_SERVER_ERROR, ["message" => "Failed to create user"]);
        }
        return Response::json(HttpStatus::CREATED, ["message" => "User created successfully", "user" => $user]);
    }

    /**
     * Handle user logout.
     *
     * @return Response
     */
    public function logout(): Response
    {
        // Logic to handle user logout
        // This is a placeholder implementation
        return Response::json(HttpStatus::OK, ["message" => "User logged out successfully"]);
    }


    public function deleteUser()
    {
        $user = Utils::get_user_from_basic_auth();
        if($user == null)
        {
            throw new UnauthorizedException("Not authorized");
        }

        $userId = $this->request->path()->get('userId');
        if($userId == null)
        {
            throw new Exception("userId is required.");
        }

        if ($user['user_id'] != $userId) {
            throw new Exception("You are not the onwer of this account.");
        }

        $this->userService->deleteById($userId);
        return Response::json(HttpStatus::OK, ["message"=>"User deleted successfully."]);

    }


    public function getLoggedInUser()
    {
        $user = Utils::get_user_from_basic_auth();
        if($user == null)
        {
            throw new UnauthorizedException("Not authorized");
        }

        return Response::json(HttpStatus::OK, $this->userService->getUserById($user['user_id']));
    }

    public function getUserById()
    {
        $userId = $this->request->path()->get('userId');
        if($userId == null)
        {
            throw new Exception("User id is required.");
        }

        $user = $this->userService->getUserById($userId);
        return Response::json(HttpStatus::OK, ["user_id"=>$user['user_id'], "username"=>$user['username'], "name"=>$user['name']]);
    }


    public function updateUser()
    {
        $userId = $this->request->path()->get('userId');
        if($userId == null)
        {
            throw new Exception("User id is required.");
        }

        $user = Utils::get_user_from_basic_auth();
        if($user == null)
        {
            throw new Exception("Not authorized");
        }

        if($user['user_id'] != $userId)
        {
            throw new Exception("You are not owner of this account.");
        }

        $userData = $this->request->body()->data();
        if (empty($userData)) {
            return Response::json(HttpStatus::BAD_REQUEST, ["message" => "User data is required"]);
        }

        $savedUser = $this->userService->getUserById($userId);

        if($savedUser == null){
            throw new Exception("User not found for id: ".$userId);
        }

        $this->userService->updateUser($userId, $userData);
        return Response::json(HttpStatus::OK, ["message"=>"User updated successfully."]);
    }

    public function getAllUsers()
    {
        $users = $this->userService->getAllUsers();
        foreach ($users as &$user) {
            unset($user['password']); // Remove password from the response
        }
        return Response::json(HttpStatus::OK, $users);
    }
}

