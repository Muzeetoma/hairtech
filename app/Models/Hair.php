<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use App\Models\Enums\HairType;
use App\Models\Enums\HairColor;

class Hair extends Model
{
    use HasFactory;

    public static function hairTypes(): array
    {
        return HairType::cases();
    }

    public static function hairColors(): array
    {
        return HairColor::cases();
    }

    protected $fillable = [
        'details',
        'images'
     ];
    
    protected function casts(): array
    {
        return [
            'details' => AsArrayObject::class,
            'images' => AsArrayObject::class,
            'type' => HairType::class,
            'color' => HairColor::class,
        ];
    }
}
