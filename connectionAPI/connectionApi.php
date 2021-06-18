<?php

class connectionApi {
    private string $url;
    private \CurlHandle $curlObj;

    public function __construct(string $url){
        $this-> url= $url;
        $this-> curlObj = curl_init();
    }

    public function getUrl(){
        return $this-> url;
    }

    public function getCurlObj(){
        return $this-> curlObj;
    }

    public function prepareCurl(string $url, \CurlHandle $curlObj){
        $headers= array(
            'Accept'=> 'application/json',
            'Content-type'=> 'application/json',
        );

        $options= array(
            CURLOPT_URL=> $url,
            CURLOPT_HTTPHEADER=> $headers,
            CURLOPT_RETURNTRANSFER=> true,
        );

        curl_setopt_array($curlObj, $options);
    }

    public function getDataFromApi(){
        $this-> prepareCurl($this-> getUrl(), $this-> getCurlObj());

        $responseApi= curl_exec($this-> getCurlObj());

        $responseApi= json_decode($responseApi);

        foreach($responseApi as $abc){
            echo "##################################### Estado ############################################################\n";
            print_r($abc);
            echo "\n\n";
        }

        return $responseApi;
    }
}

$conApi= new connectionApi('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');

$conApi-> getDataFromApi();
