<?php

namespace App\Mapper;
use App\Dto\AddHairItemDto;
use App\Models\HairItem;

class HairItemMapper{

    public static function toHairItem(AddHairItemDto $addHairDto): HairItem{
        $hair = new HairItem();
        $hair->type = $addHairDto->hairItemType;
        $hair->quality = $addHairDto->hairItemQuality;
        $hair->adhesion = $addHairDto->hairItemAdhesion;
        $hair->description = $addHairDto->hairDescription;
        $hair->dimensions = $addHairDto->hairItemDimension;
        $hair->details= HairItemMapper::mapToDetail($addHairDto->hairDetails);
        $hair->images= $addHairDto->images;
        $hair->price = $addHairDto->hairItemPrice;
        $hair->discount = $addHairDto->hairItemDiscount;
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

