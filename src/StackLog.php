<?php


namespace stacklogio;

use GuzzleHttp\Client;


class StackLog
{
    private $secretKey;
    private $bucketId;
    private $channel;
    public $allowAllException;

    function __construct($secretKey, $bucketKey)
    {
        $this->init($secretKey, $bucketKey);
    }

    private function init($secretKey, $bucketKey){
        $this->secretKey = $secretKey;
        $this->bucketId = $bucketKey;
        $this->allowAllException = true;
        $this->channel = "WY2JUnJaz";

        set_exception_handler(function ($e){
            if($this->allowAllException) {
                //$this->fatal(print_r(debug_backtrace()));
                $this->error($e->getMessage(). ":" .$e->getTraceAsString());
            }
        });


    }

    public function info($message){
        return $this->pushMessage([
            "message" => $message,
            "type" => 1
        ]);
    }

    public function warning($message){
        return $this->pushMessage([
            "message" => $message,
            "type" => 2
        ]);
    }

    public function debug($message){
        return $this->pushMessage([
            "message" => $message,
            "type" => 3
        ]);
    }

    public function error($message){
        return $this->pushMessage([
            "message" => $message,
            "type" => 4
        ]);
    }

    public function fatal($message){
        return $this->pushMessage([
            "message" => $message,
            "type" => 5
        ]);
    }

    private function pushMessage($payload){


        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => "http://www.stacklog.io:8873",
        ]);

        try{
            $response = $client->request('POST', "/api/v1/sandbox/log/create/" . $this->bucketId, [
                "headers" => [
                    "secretKey" => $this->secretKey,
                    'Accept' => 'application/json; charset=utf-8'
                ],
                'json' => [
                    "logMessage" => $payload["message"],
                    "logTypeId" => $payload["type"],
                    "stackKey" => $this->channel
                ]
            ]);
        }
        catch (\Exception $e){
            print("Status Code:{$e->getTraceAsString()}");
        }


       //print("Status Code:{$response->getStatusCode()}");

        return $response->getBody()->getContents();
    }

}