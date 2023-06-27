<?php

namespace App\Utils;

class Functions {
    public static function setResponse($res, string $errorMsg = 'Errore') {
        if(!$res || empty($res) || (is_array($res)&&count($res) == 0)) {
            return response()->json(['message' => $errorMsg], 401);
        }

        return response()->json(['data' => $res], 200);
    }
}