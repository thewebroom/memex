<?php
namespace Memex\Request;

class ShipmentRequest
{
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getParams($shipment_request)
    {
        $token = new TokenObject($this->user,$this->password);
        $location = new LocationObject();
        $parcels_obj = new ParcelObject();
        $result = [
            'token' => $token->transform(),
            'shipmentRequest'   => [
                'ServiceId'             => 38,
                'ShipFrom'              => $location->transform($shipment_request['ship_from']),
                'ShipTo'                => $location->transform($shipment_request['ship_to']),
                'Parcels'               => ['Parcel'=>$parcels_obj->transform($shipment_request['parcels'])],
                'COD'                   => [
                    'Amount'                => $shipment_request['cod'],
                    'RetAccountNo'          => 0
                ],
                'InsuranceAmount'       => $shipment_request['insurance_amount'],
                'LabelFormat'           => $shipment_request['label_format'],
                'MPK'                   => '',
                'ContentDescription'    => $shipment_request['content_description'],
                'RebateCoupon'          => 0,
                'AdditionalServices'    => [
                    'AdditionalService' => [
                        ['Code'=>'SSMS']
                    ]
                ],
            ]
        ];
        if(isset($shipment_request['no_eu'])){
            $result['shipmentRequest']['NoEu'] = $shipment_request['no_eu'];
        }
        return [$result];
    }
}
