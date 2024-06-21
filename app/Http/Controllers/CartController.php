<?php

namespace App\Http\Controllers;

use App\Dto\CartItemDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Requests\GetProductsRequest;
use App\Services\CartService;

class CartController extends Controller{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    
    public function getCartItems(GetProductsRequest $req){
       return $this->cartService->getCartItems($req->cartItems);
    }
    public function addToCart(CartRequest $req){
        $cartItemDto = new CartItemDto(
            $req->item,
            $req->id,
        );
        $this->cartService->addToCart($cartItemDto);
    }
    public function removeFromCart(CartRequest $req){
        $cartItemDto = new CartItemDto(
            $req->item,
            $req->id,
        );
        $this->cartService->removeFromCart($cartItemDto);
    }
    public function deleteFromCart(CartRequest $req){
        $cartItemDto = new CartItemDto(
            $req->item,
            $req->id,
        );
        $this->cartService->deleteFromCart($cartItemDto);
    }

}