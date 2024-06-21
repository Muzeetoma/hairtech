<?php
namespace App\Services;

use App\Dto\CartItemDto;
use App\Services\HairItemService;
use App\Services\HairService;
use App\Models\Enums\CartItem;
use Illuminate\Support\Facades\Log;

class CartService{

    private $hairService;
    private $hairItemService;

    private string $sessionKey = 'cart';
    private int $maxItems = 10;

    public function __construct(HairService $hairService,HairItemService $hairItemService)
    {
        $this->hairItemService = $hairItemService;
        $this->hairService = $hairService;
    }

    public function getCartItems($jsonString)
    {
        try{
            if (empty($jsonString) || $jsonString === 'null') {
                return collect();
            }
        
            $array = json_decode($jsonString, true);
        
            if (json_last_error() !== JSON_ERROR_NONE) {
                return collect();
            }
        
            $decodedArray = array_map('json_decode', $array);
        
            $cartItemCollections = collect($decodedArray);
        
            $hairIds = $cartItemCollections->where('cartItem', CartItem::HAIR->value)->pluck('itemId');
            $hairItemIds = $cartItemCollections->where('cartItem', CartItem::HAIR_ITEM->value)->pluck('itemId');
        
            $hairItems = $hairItemIds->isEmpty() 
            ? collect() 
            : $this->hairItemService->getHairItemsIn($hairItemIds->toArray());

            $hairs = $hairIds->isEmpty() 
            ? collect() 
            : $this->hairService->getHairProductsIn($hairIds->toArray());
        
            $cartProducts = array_merge($hairItems->toArray(),$hairs->toArray());
            
            Log::info($cartProducts);
            
            return $cartProducts;
        }catch(\Exception $ex){
            Log::error('Server Error:: Reading items in cart: ' . $ex->getMessage()); 
        }
    }
    public function countCart($cart){
        $cartItemCount = 0;
        foreach ($cart as $item) {
            $decodedItem = json_decode($item, true);
            $cartItemCount += $decodedItem['count'];
        }
        session()->put('cart_count',$cartItemCount);
    }
    public function addToCart(CartItemDto $cartItemDto)
    {
       try{
        $cart = session()->get($this->sessionKey, []);
        $itemExists = false;
    
        foreach ($cart as &$item) {
            $decodedItem = json_decode($item, true);
            if ($decodedItem['itemId'] === $cartItemDto->itemId 
                && $decodedItem['cartItem'] === $cartItemDto->cartItem) {
                if($this->maxItems > $decodedItem['count']){
                    $decodedItem['count']++;
                    $item = json_encode($decodedItem);
                }
                $itemExists = true;
                break;
            }
        }
        if (!$itemExists) {
            $cart[] = json_encode($cartItemDto);
        }
        session()->put($this->sessionKey, $cart);
        $this->countCart($cart);
        Log::info(session()->get($this->sessionKey));
       }catch(\Exception $ex){
            Log::error('Server Error:: Adding to cart: ' . $ex->getMessage()); 
       }
    }
    public function removeFromCart(CartItemDto $cartItemDto){
        try{
            $cart = session()->get($this->sessionKey,[]);

            $lastItemIndex = false;
      
            foreach($cart as $index => &$item){
              $decodedItem = json_decode($item, true);
              if ($decodedItem['itemId'] === $cartItemDto->itemId 
                  && $decodedItem['cartItem'] === $cartItemDto->cartItem) {
                  if($decodedItem['count'] <= 1){
                      $lastItemIndex = $index;
                  }else{
                      $decodedItem['count']--; 
                      $item = json_encode($decodedItem);
                  }
                  break;
              }
            }
      
            if($lastItemIndex !== false){
              Log::info('unset item', ['item' => $cart[$lastItemIndex]]);
              unset($cart[$lastItemIndex]);
              $cart = array_values($cart);
            }
            session()->put($this->sessionKey, $cart);
            $this->countCart($cart);
            Log::info(session()->get($this->sessionKey));
        }catch(\Exception $ex){
            Log::error('Server Error:: removing from cart: ' . $ex->getMessage()); 
        }
    }
    public function deleteFromCart(CartItemDto $cartItemDto){
        try{
            $cart = session()->get($this->sessionKey,[]);

            $itemIndexToDelete = false;
      
            foreach($cart as $index => &$item){
              $decodedItem = json_decode($item, true);
              if ($decodedItem['itemId'] === $cartItemDto->itemId 
                  && $decodedItem['cartItem'] === $cartItemDto->cartItem) {
                    $itemIndexToDelete = $index;
                    break;
              }
            }
      
            if($itemIndexToDelete !== false){
              Log::info('unset item', ['item' => $cart[$itemIndexToDelete]]);
              unset($cart[$itemIndexToDelete]);
              $cart = array_values($cart);
            }
            session()->put($this->sessionKey, $cart);
            $this->countCart($cart);
            Log::info(session()->get($this->sessionKey));
        }catch(\Exception $ex){
            Log::error('Server Error:: removing from cart: ' . $ex->getMessage()); 
        } 
    }
}