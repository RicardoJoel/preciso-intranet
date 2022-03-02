<?php

namespace App\Services;

use App\Ubigeo;

class Ubigeos
{
    public function get()
    {
        $ubigeos = Ubigeo::orderBy('name')->get();
        $array = [];
        foreach ($ubigeos as $ubigeo) {
            $array[$ubigeo->id] = $ubigeo->name;
        }
        return $array;
    }
}