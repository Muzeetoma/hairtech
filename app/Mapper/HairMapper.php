<?php

namespace App\Mapper;
use App\Dto\AddHairDto;
use App\Models\Hair;

class HairMapper{

    public static function toHair(AddHairDto $addHairDto): Hair{
        $hair = new Hair();
        $hair->type = $addHairDto->hairType;
        $hair->color = $addHairDto->hairColor;
        $hair->length = $addHairDto->hairLength;
        $hair->description = $addHairDto->hairDescription;
        $hair->price = $addHairDto->hairPrice;
        $hair->discount = $addHairDto->hairDiscount;
        $hair->details= HairMapper::mapToDetail($addHairDto->hairDetails);
        $hair->images= $addHairDto->images;
        return $hair;
    }

    public static function mapToDetail($hairDetails){
        $details = [];
        foreach($hairDetails as $detail){
            $details[] = $detail['detail'];
        }
        return $details;
    }
}

