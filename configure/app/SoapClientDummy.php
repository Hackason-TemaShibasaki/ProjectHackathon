<?php

    class SoapClientDummy extends SoapClient {

        public function __doRequest($request, $location, $action, $version, $one_way = 0)
        {
            // Garoon SOAP API need not ns2 namespace (bug?)., so ommit this modifier
            // $request = preg_replace('/xmlns:ns2/', 'xmlns:myns', $request);
            //$request = encodeString2UTF8($request);
//             $request = preg_replace('/ xmlns:ns1="http:\/\/wsdl.cybozu.co.jp\/util_api\/2008"/', '', $request);
//             $request = preg_replace('/ xmlns="http:\/\/schemas.xmlsoap.org\/ws\/2003\/03\/addressing"/', '', $request);
//             $request = preg_replace('/ xmlns:wsu="http:\/\/schemas.xmlsoap.org\/ws\/2002\/12\/secext"/', '', $request);
//             $request = preg_replace('/ xmlns:ns1="http:\/\/wsdl.cybozu.co.jp\/base\/2008"/', '', $request);
//             $request = preg_replace('/ xmlns:ns2="http:\/\/wsdl.cybozu.co.jp\/util_api\/2008"/', '', $request);
            $request = preg_replace('/<ns2:/', '<', $request);
            $request = preg_replace('/<\/ns2:/', '</', $request);
//             $request = preg_replace('/env=/', 'soap=', $request);
//             $request = preg_replace('/<env:/', '<soap:', $request);
//             $request = preg_replace('/<\/env:/', '</soap:', $request);
//             $request = preg_replace('/<ns1:/', '<', $request);
//             $request = preg_replace('/<\/ns1:/', '</', $request);
//             $request = preg_replace('/jp;/', 'jp', $request);
//             $request = preg_replace('/<parameters\/>/', '<parameters></parameters>', $request);
//             return parent::__doRequest($request, $location, $action, $version, $one_way);
$result = parent::__doRequest($request, $location, $action, $version, $one_way);
return $result;
        }


    }
