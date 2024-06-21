<?php
namespace App\Utils;

class PriceUtils{

    public static function getOriginalPrice(int $price, int $discount): int {
        if ($discount < 0 || $discount > 100) {
            throw new \InvalidArgumentException('Discount must be between 0 and 100.');
        }
        $newPrice = $price * (1 - $discount / 100);
        return (int) round($newPrice);
    }
}
