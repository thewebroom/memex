<?php
namespace Memex\Request;

class LocationObject
{
    public function __construct()
    {

    }

    public function transform($location)
    {
        $data = [
            'Name'              => $location['name'],
            'Address'           => $location['address'],
            'City'              => $location['city'],
            'PostCode'          => $location['post_code'],
            'CountryCode'       => $location['country_code'],
            'Person'            => $location['person_name'],
            'Contact'           => $location['phone'],
            'Email'             => $location['email'],
            'IsPrivatePerson'   => $location['is_private_person'],
        ];
        if(isset($location['point_id'])){
            $data['PointId'] = $location['point_id'];
        } else {
            $data['PointId'] = '';
        }

        if(isset($location['client_tax_id'])){
            $data['ClientTaxId'] = $location['client_tax_id'];
        } else {
            $data['ClientTaxId'] = '';
        }

        return $data;
    }
}
