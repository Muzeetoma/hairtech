<?php

namespace App\Services;
use App\Dto\AddHairDto;
use App\Dto\AddHairItemDto;
use App\Mapper\HairItemMapper;
use App\Models\Enums\CartItem;
use App\Models\Enums\HairItemType;
use App\Models\HairItem;
use App\Repositories\HairItemRepository;
use App\Services\FileUploadService;
use App\Utils\PriceUtils;

class HairItemService{

    private $fileUploadService;
    private $hairItemRepository;

    public function __construct(FileUploadService $fileUploadService,
                                HairItemRepository $hairItemRepository){
        $this->fileUploadService = $fileUploadService;
        $this->hairItemRepository = $hairItemRepository;
    }
    public function getHairItemsIn(array $Ids){
        try{
            $hairItems = $this->hairItemRepository->getHairItemsIn($Ids);
            $newHairItems = $hairItems->map(function ($hair){
                $hair->cartItemType = CartItem::HAIR_ITEM;
                return $hair;
            });
            return $newHairItems;
        }catch(\Exception $ex){
            session()->flash('error','Something went wrong: ' . $ex->getMessage()); 
        }
    }
    public function getTwoRandomHairItems(): array {
        $items = [
            $this->getRandomHairItemWithOriginalPrice(HairItemType::FRONTAL->value),
            $this->getRandomHairItemWithOriginalPrice(HairItemType::CLOSURE->value)
        ];
        return array_filter($items, function($item) {
            return !is_null($item);
        });
    }
    private function getRandomHairItemWithOriginalPrice(string $type) {
        $item = $this->hairItemRepository->getRandomHairItems($type);
        if ($item === null) {
            return null;
        }
        $item->originalPrice = PriceUtils::getOriginalPrice($item->price, $item->discount);
        return $item;
    }
    public function getHairsByType($hairType){
        $hairGroups = $this->hairItemRepository->getHairItemsByType($hairType);
        if(empty($hairGroups)){
            session()->flash('error','Hair items not found'); 
            return;
        }
        return $hairGroups;
    }
    public function getHairItemById($id){
        $hairItem = $this->hairItemRepository->getByid($id);
        if(empty($hairItem)){
            session()->flash('error','Hair product not found'); 
            return;
        }
        $hairItem->originalPrice = PriceUtils::getOriginalPrice($hairItem->price,$hairItem->discount);
        return $hairItem;
    }
    public function addHairItem(AddHairItemDto $addHairItemDto){
        try{
            $addHairItemDto->images = $this->fileUploadService->uploadImages($addHairItemDto->images);
            if(empty($addHairItemDto->images)){
                throw new \Exception('Failed to upload image');  
            }
            $hairItem = HairItemMapper::toHairItem($addHairItemDto);
            if($hairItem->save()){
                session()->flash('message','Hair item created successfully');
            }
        }catch(\Exception $ex){
            session()->flash('error','Something went wrong: ' . $ex->getMessage()); 
        }
    }
}