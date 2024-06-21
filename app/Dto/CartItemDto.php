<?php

namespace App\Dto;

use App\Models\Enums\CartItem;

class CartItemDto{
    public $cartItem;
    public int $itemId;
    public int $count;

    public function __construct(string $cartItem, 
                                int $itemId,
                                int $count = 1){
        $this->cartItem = $cartItem;
        $this->itemId = $itemId;
        $this->count = $count;
    }

    public static function cartItems(): array
    {
        return CartItem::cases();
    }

}