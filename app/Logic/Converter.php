<?php

namespace App\Logic;

class Converter
{
	public  function ArrayContainsObjects($data)
	{
        $array = [];
        foreach($data as $key => $value) {
            array_push($array, 
            [
                'title' => $value,
                'id' => $key,
            ]);
        }
        return $array;
    }
}