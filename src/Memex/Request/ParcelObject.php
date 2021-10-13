<?php
namespace Memex\Request;

class ParcelObject
{
    public function __construct()
    {

    }

    public function transform($parcels)
    {
        $result = [];
        $parcel = $parcels;
        $data = [
            'Type'    => 'Package',
            'Weight'  => $parcel['weight'],
            'IsNST'   => false,
        ];
        if(isset($parcel['length'])){
            $data['D'] = $parcel['length'];
        }
        if(isset($parcel['height'])){
            $data['W'] = $parcel['height'];
        }
        if(isset($parcel['width'])){
            $data['S'] = $parcel['width'];
        }
        $result = $data;
        return $result;
    }
}
