<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function successWithData($input)
    {
        $meta = array(
            'code' => 200,
            'message' => 'Success',
            'error' => false
        );
        $data = $input;
        $response = array(
            "meta" => $meta,
            "data" => $data
        );
        return $response;
    }
    public static function successWithoutData($msg)
    {
        $meta = array(
            'code' => 200,
            'message' => $msg,
            'error' => false
        );
        $response = array(
            "meta" => $meta,
        );
        return $response;
    }
    public static function errorCustom($code, $msg)
    {
        $meta = array(
            'code' => $code,
            'message' => $msg,
            'error' => true
        );
        $response = array(
            "meta" => $meta
        );
        return $response;
    }

    private static function arrayFlatten($array)
    {
        if (!is_array($array)) {
            return FALSE;
        }

        $result = array();
        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    public static function validateResponse($validator)
    {
        $error = $validator->messages()->toArray();
        $error = self::arrayFlatten($error);
        $error = implode(' ', $error);

        return self::errorCustom(400, $error);
    }
}
