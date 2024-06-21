<?php

namespace App\Services;
use App\Dto\AddHairDto;
use App\Mapper\HairMapper;
use App\Models\Enums\CartItem;
use App\Services\FileUploadService;
use App\Repositories\HairRepository;
use App\Utils\PriceUtils;
use Illuminate\Support\Facades\Log;


class HairService{

    private $fileUploadService;
    private $hairRepository;

    public function __construct(FileUploadService $fileUploadService,
                                HairRepository $hairRepository){
        $this->fileUploadService = $fileUploadService;
        $this->hairRepository = $hairRepository;
    }

    public function getHairProductsIn(array $Ids){
        try{
            $hairProducts = $this->hairRepository->getHairProductsIn($Ids);
            $newHairProducts = $hairProducts->map(function ($hair){
                $hair->cartItemType = CartItem::HAIR;
                return $hair;
            });
            Log::info($hairProducts);
            return $newHairProducts;
        }catch(\Exception $ex){
            session()->flash('error','Something went wrong: ' . $ex->getMessage()); 
        }
    }
    public function groupHairByTypes(){
        return $this->hairRepository->groupHairProductsByType();
    }
    public function getHairsByType($hairType){
        $hairGroups = $this->hairRepository->getHairProductsByType($hairType);
        if(empty($hairGroups)){
            session()->flash('error','Hair product groups not found'); 
            return;
        }
        return $hairGroups;
    }
    public function getHairById($id){
        $hair = $this->hairRepository->getByid($id);
        if(empty($hair)){
            session()->flash('error','Hair product not found'); 
            return;
        }
        $hair->originalPrice = PriceUtils::getOriginalPrice($hair->price,$hair->discount);
        return $hair;
    }
    public function addHair(AddHairDto $addHairDto){
        try{
            $addHairDto->images = $this->fileUploadService->uploadImages($addHairDto->images);
            if(empty($addHairDto->images)){
                throw new \Exception('Failed to upload image');  
            }
            $hair = HairMapper::toHair($addHairDto);
            if($hair->save()){
                session()->flash('message','Hair product created successfully');
            }
        }catch(\Exception $ex){
            session()->flash('error','Something went wrong: ' . $ex->getMessage()); 
        }
    }
}