<?php

namespace App\Dto;

class AddHairItemDto{
    public $images;
    public $hairItemType;
    public $hairDetails;
    public $hairDescription;
    public $hairItemQuality;
    public $hairItemAdhesion;
    public $hairItemDimension;
    public $hairItemPrice;
    public $hairItemDiscount;

    public function __construct(?array $images, 
                                string $hairItemType, 
                                ?array $hairDetails, 
                                string $hairDescription, 
                                string $hairItemQuality,
                                string $hairItemAdhesion,
                                string $hairItemDimension,
                                string $hairItemPrice,
                                string $hairItemDiscount
                          ) {
        $this->images = $images;
        $this->hairItemType = $hairItemType;
        $this->hairDetails = $hairDetails;
        $this->hairDescription = $hairDescription;
        $this->hairItemQuality = $hairItemQuality;
        $this->hairItemAdhesion = $hairItemAdhesion;
        $this->hairItemDimension = $hairItemDimension;
        $this->hairItemPrice = $hairItemPrice;
        $this->hairItemDiscount = $hairItemDiscount;
    }
}