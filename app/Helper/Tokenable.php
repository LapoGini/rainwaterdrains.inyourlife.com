<?php

namespace App\Helper;

use Illuminate\Support\Str;

Trait Tokenable 
{
    public function setAuthToken()
    {
        $token = Str::random(80);

        $this->api_token = $token;
        $this->save();

        return $this;
    }
}