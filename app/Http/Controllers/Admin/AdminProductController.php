<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Hair;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHairRequest;
use App\Services\HairService;
use App\Dto\AddHairDto;
use App\Dto\AddHairItemDto;
use App\Http\Requests\StoreHairItemRequest;
use App\Models\HairItem;
use App\Services\HairItemService;

class AdminProductController extends Controller
{
    private $hairService; 
    private $hairItemService;

    public function __construct(HairService $hairService,
                                HairItemService $hairItemService){
        $this->hairService = $hairService;
        $this->hairItemService = $hairItemService;
    }

    public function showHairCreationForm(){
        $hairTypes = Hair::hairTypes();
        $hairColors = Hair::hairColors();
        
        return Inertia::render('Admin/Product/HairCreationForm',[
            'hairTypes' => $hairTypes,
            'hairColors' => $hairColors,
        ]);
    }

    public function showHairItemCreationForm(){
        $hairItemTypes = HairItem::hairItemTypes();
        $hairItemQualities = HairItem::hairItemQualities();
        $hairItemAdhesions = HairItem::hairItemAdhesions();
        $hairItemDimensions = HairItem::hairItemDimensions();

        return Inertia::render('Admin/Product/HairItemCreationForm',[
            'hairItemTypes' => $hairItemTypes,
            'hairItemQualities' => $hairItemQualities,
            'hairItemAdhesions' => $hairItemAdhesions,
            'hairItemDimensions' => $hairItemDimensions,
        ]);
    }

    public function storeHair(StoreHairRequest $req){
        $addHairDto = new AddHairDto(
            $req->images,
            $req->type,
            $req->details,
            $req->description,
            $req->length,
            $req->color,
            $req->price,
            $req->discount
        );
        $this->hairService->addHair($addHairDto);
    }

    public function storeHairItem(StoreHairItemRequest $req){
        $addHairItemDto = new AddHairItemDto(
            $req->images,
            $req->type,
            $req->details,
            $req->description,
            $req->quality,
            $req->adhesion,
            $req->dimension,
            $req->price,
            $req->discount
        );
        $this->hairItemService->addHairItem($addHairItemDto);
    }
}
