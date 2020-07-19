<?php


namespace stacklogio;


class StackLog
{
    private $secretKey;
    private $bucketId;

    function __construct($key, $bucketId)
    {
        $this->init($key, $bucketId);
    }

    private function init($key, $bucketId){
        $this->secretKey = $key;
        $this->bucketId = $bucketId;
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
        $data = json_encode([
            "logMessage" => $payload["message"],
            "logTypeId" => $payload["type"]
        ]);


        $curl = curl_init("http://192.168.43.244:8873/api/v1/sandbox/log/create/".$this->bucketId);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'authorization:'.$this->secretKey));

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

}