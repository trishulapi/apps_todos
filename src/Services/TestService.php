<?php

namespace App\Services;

use TrishulApi\Core\Enums\HttpStatus;
use TrishulApi\Core\Http\Response;

class TestService
{
    public function test(): Response
    {
        return Response::json(HttpStatus::OK, ["ok" => "ok"]);
    }
}