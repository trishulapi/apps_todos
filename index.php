<?php
require_once "vendor/autoload.php";

use App\Controllers\UserController;
use App\Dto\LoginDto;
use App\Middlewares\BasicAuthMiddleware;
use App\Services\TestService;
use TrishulApi\Core\App;
use TrishulApi\Core\Http\Request;
use TrishulApi\Core\Http\RequestType;
use TrishulApi\Core\Http\Router;
include_once "src/Routes.php";

$app = new App();

/* * This is the main entry point for the application.
 * It initializes the application and handles the request.
 * 
 * Project Structure
 * 
 * src 
 *  ├── app
 *  │   ├── models
 *  │   ├── repos
 *  │   ├── services 
 *  |   logs
 *  │   ├── 2025-06-01_log.log
 * index.php
 * .env
 * composer.json
 * composer.lock
 * 
 * 
 * * This file is responsible for bootstrapping the application.    
 * 
 * The TrishulApi\Core\App class is responsible for initializing the application.
 * 
 * $app = new \TrishulApi\Core\App();
 * 
 * Router::get("/",["hello"=>"world"]);
 * 
 * 
 * 
 * it has several useful and must have methods to configure the application.
 * 
 * 
 * 
 *
 *  */


 Router::include($routes);
 Router::get("/test", TestService::class."@test");
 Router::get("/", ["welcome"=>"Trishul APIs Developers "]);
 Router::post("/login", UserController::class."@login", requestBody: LoginDto::class, response_type: ["message" => "string", "user" => "object"]);
$app->set_env_path(".env");
Router::set_global_middlewares([BasicAuthMiddleware::class], except:["/"=>RequestType::GET,"/login"=>RequestType::GET, "/users/"=>RequestType::POST]);
$app->set_allowed_domains(["*"]);
$app->set_log_dir("src/logs");
$app->get_swagger()->set_security_schemes([
    "basicAuth" => [
        "type" => "http",
        "scheme" => "basic",
        "description" => "Basic Authentication"
    ]
]);

$app->start();
