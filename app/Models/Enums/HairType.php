<?php

namespace App\Models\Enums;

enum HairType: string
{
    case STRAIGHT = 'straight';
    case WAVY = 'wavy';
    case CURLY = 'curly';
    case BOUNCY = 'bouncy';
}