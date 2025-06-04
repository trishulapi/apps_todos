<?php

namespace App\Middlewares;

use App\Models\User;
use App\Services\UserService;
use App\Utils;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\Response;
use TrishulApi\Core\Middleware\MiddlewareInterface;

class BasicAuthMiddleware implements MiddlewareInterface
{
    private UserService $userService;
    
    public function handle_request($request):Request
    {
        $this->userService = new UserService($request);
        // Check if the request has the Authorization header
        if (!$request->header()->has('Authorization')) {
            throw new \Exception('Authorization header not found', 401);
        }

        // Get the Authorization header value
        $authHeader = $request->header()->get('Authorization');
        if (empty($authHeader)) {
            throw new \Exception('Authorization header is empty', 401);
        }

        // Check if the header starts with 'Basic '
        if (strpos($authHeader, 'Basic ') !== 0) {
            throw new \Exception('Authorization should start with Basic', 401);

        }

        // Decode the base64 encoded credentials
        $credentials = base64_decode(substr($authHeader, 6));
        list($username, $password) = explode(':', $credentials);

        // Validate the credentials
        $user = $this->userService->login($username, $password);
        if (!$user) {
            throw new \Exception('Invalid credentials', 401);
        }

        Utils::set_user_from_basic_auth($user);
        // If credentials are valid, proceed to the next middleware or request handler
        return $request;
    }

    public function handle_response(Response $response):Response
    {
        // This middleware does not modify the response
        return $response;
    }
}