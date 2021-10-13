<?php
namespace Memex\Request;

class TokenObject
{
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function transform()
    {
        return [
            'UserName'  => $this->user,
            'Password'  => $this->password
        ];
    }
}
