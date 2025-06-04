<?php

namespace App\Dto;

class UserDto
{
    public string $user_id;
    public string $username;
    public ?string $password = null; // Optional, for create/update operations
    public ?string $name = null; // Optional, for create/update operations

    public function __construct(
        string $user_id,
        string $username,
        ?string $password = null,
        ?string $name = null,
    ) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }
}