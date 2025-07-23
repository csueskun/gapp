<?php

namespace App\Services;

class DIANServices
{
    protected $numberConversionWsdl = 'https://www.dataaccess.com/webservicesserver/NumberConversion.wso?WSDL';

    public function numberToWords($number)
    {
        try {
            $client = new \SoapClient($this->numberConversionWsdl, [
                'trace' => 1,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            // working no xml
            $params = ['ubiNum' => $number];
            $response = $client->__soapCall('NumberToWords', [$params]);
            return response()->json([
                'result' => $response->NumberToWordsResult ?? $response,
            ]);
        } catch (\SoapFault $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function convertWithRawXml($number)
    {
        try {
            $client = new \SoapClient(null, [
                'location' => $this->numberConversionWsdl,
                'uri'      => 'http://www.dataaccess.com/webservicesserver/',
                'trace'    => 1,
            ]);

            // Create raw SOAP XML request
            $soapEnvelope = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://www.dataaccess.com/webservicesserver/">
   <soapenv:Header/>
   <soapenv:Body>
      <web:NumberToWords>
         <web:ubiNum>$number</web:ubiNum>
      </web:NumberToWords>
   </soapenv:Body>
</soapenv:Envelope>
XML;

            // Send raw SOAP request
            $response = $client->__doRequest(
                $soapEnvelope,
                $this->numberConversionWsdl,
                'http://www.dataaccess.com/webservicesserver/NumberConversion.wso/NumberToWords',
                1 // SOAP 1.1
            );

            // Return raw XML response
            return response($response, 200)->header('Content-Type', 'application/xml');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
