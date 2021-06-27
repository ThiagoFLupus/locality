<?php

namespace Locality\connectionAPI;

class ConnectionApi {
    private string $url;
    private \CurlHandle $curlObj;

    public function __construct(){
        $this-> curlObj = curl_init();
    }

    public function setUrl(string $url){
        $this-> url = $url;
    }

    public function getUrl(){
        return $this-> url;
    }

    public function getCurlObj(){
        return $this-> curlObj;
    }

    public function prepareCurl(){
        $headers= array(
            'Accept'=> 'application/json',
            'Content-type'=> 'application/json',
        );

        $options= array(
            CURLOPT_URL=> $this-> getUrl(),
            CURLOPT_HTTPHEADER=> $headers,
            CURLOPT_RETURNTRANSFER=> true,
        );

        curl_setopt_array($this-> curlObj, $options);
    }

    public function exec(){
        $responseApi= curl_exec($this-> getCurlObj());
        $responseApi= json_decode($responseApi);

        return $responseApi;
    }
}