<?php
namespace Memex;

use Memex\Request\CancelShipmentRequest;
use Memex\Request\GetAvailableServicesRequest;
use Memex\Request\ShipmentRequest;
use SoapClient;

class Memex
{
    protected $client = null;
    public function __construct($url, $user,$password)
    {
        $this->url = $url;
        $this->user = $user;
        $this->password = $password;
    }

    private function createClient()
    {
        $soap_options = [
            'trace'         => 1,
            'exceptions'    => 1,
            //'cache_wsdl'    => WSDL_CACHE_NONE,
        ];

        return new SoapClient( $this->url, $soap_options);
    }

    public function call($method, $params)
    {
        if (is_null($this->client)) {
            $this->client = $this->createClient();
        }
        try {
            $results = $this->client->__soapCall($method, $params);
        } catch (SoapFault $e) {
            error_log('soap exception ' . print_r($e->getMessage(), true));
            return [
                'error'         => true,
                'message'       => $e->getMessage()
            ];
        }
        return $results;
    }

    public function getAvailableServices($ready_date,$ship_from,$ship_to,$parcels,$cod,$insurance_amount)
    {
        $request = new GetAvailableServicesRequest($this->user,$this->password);
        $request_params = $request->getParams($ready_date,$ship_from,$ship_to,$parcels,$cod,$insurance_amount);
        return $this->call('GetAvailableServices', $request_params);
    }
    public function getLabelPdfApi($api_id)
    {
        $params = [
            "token" => [
                'UserName'  => $this->user,
                'Password'  => $this->password
            ],
            "getLabelRequest" => [
                "PackageNo" =>  [
                    "string" => $api_id
                ]
            ]
        ];
        $response = $this->call('GetLabel', [$params]);
        if(is_array($response)&&isset($response['error'])){
            return $response;
        }
        if(!isset($response->GetLabelResult->responseDescription) || ($response->GetLabelResult->responseDescription !== 'Success')) {
            error_log('GetLabel resp ' . print_r($response, true));
            return [
                'error'         => true,
            ];
        }
        return $response->GetLabelResult->LabelData->Label->MimeData;
    }

    public function getLabels($ids)
    {
        $params = [
            "token" => [
                'UserName'  => $this->user,
                'Password'  => $this->password
            ],
            "getLabelRequest" => [
                "PackageNo" =>  $ids
            ]
        ];
        $response = $this->call('GetLabel', [$params]);
        if(is_array($response)&&isset($response['error'])){
            return $response;
        }

        if(!isset($response->GetLabelResult->responseDescription) || ($response->GetLabelResult->responseDescription !== 'Success')) {
            error_log('GetLabel resp ' . print_r($response, true));
            return [
                'error'         => true,
            ];
        }
        return $response->GetLabelResult->LabelData->Label->MimeData;
    }

    public function getTracking($id)
    {
        $params = [
            "token" => [
                'UserName'  => $this->user,
                'Password'  => $this->password
            ],
            "PackageNo" =>  $id
        ];
        var_dump($params);
        $response = $this->call('GetTracking', [$params]);
        if(is_array($response)&&isset($response['error'])){
            return $response;
        }
        return $response;
    }

    public function cancelShipment($id)
    {
        $request = new CancelShipmentRequest($this->user,$this->password);
        return  $this->call('CancelShipment', $request->getParams($id));
    }

    public function cancelShipments($ids)
    {
        $request = new CancelShipmentRequest($this->user,$this->password);
        return  $this->call('CancelShipment', $request->getParams($ids));
    }

    public function createShipment($shipment_request)
    {
        $request = new ShipmentRequest($this->user,$this->password);
        $response =  $this->call('CreateShipment', $request->getParams($shipment_request));
        if(is_array($response)&&isset($response['error'])){
            return $response;
        }
        if(!isset($response->CreateShipmentResult->responseDescription) || ($response->CreateShipmentResult->responseDescription !== 'Success')) {
            error_log('CreateShipment resp ' . print_r($response, true));
            return [
                'error'         => true,
                'response_code' => $response->CreateShipmentResult->responseCode,
                'details'       => $response->CreateShipmentResult->responseDescription
            ];
        }
        if(isset($response->CreateShipmentResult->ParcelData->Label->ParcelID)) {
            $api_id = $response->CreateShipmentResult->ParcelData->Label->ParcelID;
            $response->CreateShipmentResult->ParcelData->Label->MimeData = "[" . strlen($response->CreateShipmentResult->ParcelData->Label->MimeData) . "]";
        }
        elseif(isset($response->CreateShipmentResult->PackageNo)) {
            $api_id = $response->CreateShipmentResult->PackageNo;
        } else {
            return [
                'error'         => true,
                'response_code' => '',
                'details'       => 'Unrecogized API response'
            ];
        }
        return ['api_id'=>$api_id];
    }
}
