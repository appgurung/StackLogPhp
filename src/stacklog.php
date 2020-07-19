<?php


namespace stacklogio;


class StackLog
{
    private static $secretKey;
    private static $bucketId;

    public static function init($key, $bucket=""){
        self::$secretKey = $key;
        self::$bucketId = $bucket;
    }

    public static function info($payload){
        return self::pushMessage([
            "message" => $payload["message"],
            "type" => 1
        ]);
    }

    public static function warning($payload){
        return self::pushMessage([
            "message" => $payload["message"],
            "type" => 2
        ]);
    }

    public static function debug($payload){
        return self::pushMessage([
            "message" => $payload["message"],
            "type" => 3
        ]);
    }

    public static function error($payload){
        return self::pushMessage([
            "message" => $payload["message"],
            "type" => 4
        ]);
    }

    public static function fatal($payload){
        return self::pushMessage([
            "message" => $payload["message"],
            "type" => 5
        ]);
    }

    private static function pushMessage($payload){
        $data = json_encode([
            "logMessage" => $payload["message"],
            "logTypeId" => $payload["type"]
        ]);


        $curl = curl_init("http://fcmb-it-l16572:8873/api/v1/sandbox/log/create/7C329EF3-E657-402D-A52F-3533600980DB");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, data);
        curl_setopt($curl, CURLOPT_HEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'Authorization: ' . self::$secretKey,
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

}