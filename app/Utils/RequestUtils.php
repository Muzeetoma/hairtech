<?php
namespace App\Utils;
use Illuminate\Http\Request;

class RequestUtils{

    public static function getArrayOfValuesFromUrl(Request $request, string $varName): array{
        $arr = [];
        $i = 0;
        while ($request->has($varName.$i)) {
            $arr[] = $request->input($varName.$i);
            $i++;
        }
        return $arr;
    }

    public static function getArrayOfColorValuesFromUrl(Request $request, string $varName): array{
        $arr = [];
        $i = 0;
        while ($request->has($varName.$i)) {
            $arr[] = "#" . $request->input($varName.$i);
            $i++;
        }
        return $arr;
    }
}