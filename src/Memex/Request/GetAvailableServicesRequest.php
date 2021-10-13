<?php
namespace Memex\Request;

class GetAvailableServicesRequest
{
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getParams($ready_date,$ship_from,$ship_to,$parcels,$cod,$insurance_amount)
    {
        $token = new TokenObject($this->user,$this->password);
        $location = new LocationObject();
        $parcels_obj = new ParcelObject();
        return [
            'token' => $token->transform(),
            'getAvailableServicesRequest'   => [
                'ReadyDate'         => $ready_date,
                'ShipFrom'          => $location->transform($ship_from),
                'ShipTo'            => $location->transform($ship_to),
                'Parcels'           => $parcels_obj->transform($parcels),
                'COD'               => [
                    'Amount'    => $cod
                ],
                'InsuranceAmount'   => $insurance_amount
            ]
        ];
    }
}
