<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductsRequest;
use App\Services\HairItemService;
use App\Services\HairService;

class ProductController extends Controller{

    private $hairService; 
    private $hairItemService;

    public function __construct(HairService $hairService,HairItemService $hairItemService){
        $this->hairService = $hairService;
        $this->hairItemService = $hairItemService;
    }

    public function productsIn(GetProductsRequest $req){
       return $this->hairService->getHairProductsIn($req->ids);
    }

    public function products(){
        $hairProducts = $this->hairService->groupHairByTypes();
        return Inertia::render('Products',[
            'hairProducts' => $hairProducts,
        ]);
    }

    public function product($id, $type){
        $hairProduct = $this->hairService->getHairById($id);
        $hairProductGroups = $this->hairService->getHairsByType($type);
        $hairItems = $this->hairItemService->getTwoRandomHairItems();
        return Inertia::render('Product',[
            'hairProduct' => $hairProduct,
            'hairProductGroups' => $hairProductGroups,
            'hairItems' => $hairItems,
        ]);
    }
}