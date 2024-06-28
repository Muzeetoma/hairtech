<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetProductsRequest;
use App\Models\Hair;
use App\Services\HairItemService;
use App\Services\HairService;
use App\Utils\RequestUtils;
use Illuminate\Http\Request;

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

    public function products(Request $request){
        $typesSearchParams = RequestUtils::getArrayOfValuesFromUrl($request,'type');
        $colorsSearchParams = RequestUtils::getArrayOfColorValuesFromUrl($request,'color');
        $lengthsSearchParams = RequestUtils::getArrayOfValuesFromUrl($request,'length');

        $hairProducts = $this->hairService->groupHairByTypes(
            $typesSearchParams,
            $colorsSearchParams,
            $lengthsSearchParams
        );
        $hairTypes = Hair::hairTypes();
        $hairColors = Hair::hairColors();

        return Inertia::render('Products',[
            'hairProducts' => $hairProducts,
            'hairTypes' => $hairTypes,
            'hairColors' => $hairColors,
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