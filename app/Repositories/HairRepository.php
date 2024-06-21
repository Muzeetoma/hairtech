<?php
namespace App\Repositories;
use App\Models\Hair;

class HairRepository{

    public function getHairProductsIn(array $ids){
        return Hair::whereIn('id', $ids)->get();
    }
    public function getHairProductsByType($type){
        return Hair::where('type',$type)->get();
    }
    public function getByid($id){
        return Hair::where('id',$id)->first();
    }
    public function groupHairProductsByType(){
        $hairs = Hair::selectRaw('
                type,
                length,
                JSON_UNQUOTE(JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "images", images
                    )
                )) AS images,
                JSON_UNQUOTE(JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "color", color
                    )
                )) AS colors,
                JSON_UNQUOTE(JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "price", price
                    )
                )) AS prices,
                JSON_UNQUOTE(JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "length", length
                    )
                )) AS lengths,
                JSON_UNQUOTE(JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "id", id,
                        "discount", discount
                    )
                )) AS discounts
            ')
            ->groupBy('type')
            ->groupBy('length')
            ->get();
        
    return $hairs;
    }
}