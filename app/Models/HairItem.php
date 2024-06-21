<?php

namespace App\Models;

use App\Models\Enums\HairItemAdhesion;
use App\Models\Enums\HairItemDimension;
use App\Models\Enums\HairItemQuality;
use App\Models\Enums\HairItemType;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairItem extends Model
{
    use HasFactory;

    public static function hairItemTypes(): array
    {
        return HairItemType::cases();
    }

    public static function hairItemDimensions(): array
    {
        return HairItemDimension::cases();
    }

    public static function hairItemQualities(): array
    {
        return HairItemQuality::cases();
    }

    public static function hairItemAdhesions(): array
    {
        return HairItemAdhesion::cases();
    }

    protected function casts(): array
    {
        return [
            'type' => HairItemType::class,
            'dimensions' => HairItemDimension::class,
            'quality' => HairItemQuality::class,
            'adhesion' => HairItemAdhesion::class,
            'details' => AsArrayObject::class,
            'images' => AsArrayObject::class,
        ];
    }
}
