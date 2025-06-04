<?php

namespace App\Dto;

class CreateUserDto
{
    public string $username;
    public ?string $password = null; // Optional, for create/update operations
    public ?string $name = null; // Optional, for create/update operations

    public function __construct(
        string $username,
        ?string $password = null,
        ?string $name = null,
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }
}