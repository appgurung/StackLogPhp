<?php


namespace stacklogio;


class StackLog
{
    private $secretKey;

    function __construct($key)
    {
        $this->init($key);
    }

    private function init($key){
        $this->secretKey = $key;
    }

    public function info($payload){
        return $this->pushMessage([
            "message" => $payload["message"],
            "type" => 1
        ]);
    }

    public function warning($payload){
        return $this->pushMessage([
            "message" => $payload["message"],
            "type" => 2
        ]);
    }

    public function debug($payload){
        return $this->pushMessage([
            "message" => $payload["message"],
            "type" => 3
        ]);
    }

    public function error($payload){
        return $this->pushMessage([
            "message" => $payload["message"],
            "type" => 4
        ]);
    }

    public function fatal($payload){
        return $this->pushMessage([
            "message" => $payload["message"],
            "type" => 5
        ]);
    }

    private function pushMessage($payload){
        $data = json_encode([
            "logMessage" => $payload["message"],
            "logTypeId" => $payload["type"]
        ]);


        $curl = curl_init("http://fcmb-it-l16572:8873/api/v1/sandbox/log/create/7C329EF3-E657-402D-A52F-3533600980DB");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'Authorization: ' . $this->secretKey,
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

}