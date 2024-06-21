<?php

namespace App\Dto;

class AddHairDto{
    public $images;
    public $hairType;
    public $hairDetails;
    public $hairDescription;
    public $hairLength;
    public $hairColor;
    public $hairPrice;
    public $hairDiscount;

    public function __construct(?array $images, 
                                string $hairType, 
                                ?array $hairDetails, 
                                string $hairDescription, 
                                int $hairLength, 
                                string $hairColor,
                                int $hairPrice, 
                                int $hairDiscount) {
        $this->images = $images;
        $this->hairType = $hairType;
        $this->hairDetails = $hairDetails;
        $this->hairDescription = $hairDescription;
        $this->hairLength = $hairLength;
        $this->hairColor = $hairColor;
        $this->hairPrice = $hairPrice;
        $this->hairDiscount = $hairDiscount;
    }
}