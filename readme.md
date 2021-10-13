```
require __DIR__ . '/vendor/autoload.php';

$memex = new Memex\Memex($url,$user,$pass);

createShipment 
$ready_date = date('Y-m-d H:i:s',time());
$ship_from = [
'name'                  => 'ship from name',
'address'               => 'ship from adress',
'city'                  => 'ship from city ',
'post_code'             => 'ship from post code',
'country_code'          => 'RO',
'person_name'           => 'ship from person name',
'phone'                 => 'ship from phone',
'email'                 => 'ship from email',
'is_private_person'     => false
];
$ship_to = [
'name'                  => 'ship to name',
'address'               => 'ship to address',
'city'                  => 'ship to city',
'post_code'             => 'ship to post_code',
'country_code'          => 'RO',
'person_name'           => 'ship to person_name',
'phone'                 => 'ship to phone',
'email'                 => 'ship to email',
'is_private_person'     => false
];


$shipment_request = [

'ship_from'     => $ship_from,
'ship_to'       => $ship_to,
'parcels'       => [
'weight'    => 1,
'length'    => 20,
'height'    => 10,
'width'     => 10,
],
'cod'           => 10,
'insurance_amount'  => 10,
'label_format'      => 'PDF'
];
$response = $memex->createShipment($shipment_request);


getLabelPdfApi
$memex->getLabelPdfApi($api_id);
```
