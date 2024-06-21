<?php
namespace App\Repositories;
use App\Models\HairItem;

class HairItemRepository{


    public function getHairItemsIn(array $ids){
        return HairItem::whereIn('id', $ids)->get();
    }
    public function getRandomHairItems($type)
    {
        $totalHairItems = HairItem::where('type', $type)->count();
        if ($totalHairItems === 0) {
            return null;
        }
        $randomOffset = rand(0, $totalHairItems - 1);
        return HairItem::where('type', $type)->skip($randomOffset)->first();
    }
    

    public function getHairItemsByType($type){
        return HairItem::where('type',$type)->get();
    }

    public function getByid($id){
        return HairItem::where('id',$id)->first();
    }
}