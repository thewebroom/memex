<?php
namespace Memex\Request;

class CancelShipmentRequest
{
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getParams($id)
    {
        $token = new TokenObject($this->user,$this->password);
        if(is_array($id)){
            $result = [
                'token'                 => $token->transform(),
                'cancelShipmentRequest' => ['PackageNo'   => $id]
            ];
        } else {
            $result = [
                'token'                 => $token->transform(),
                'cancelShipmentRequest' => ['PackageNo'   => [$id]]
            ];
        }
        return [$result];
    }
}
