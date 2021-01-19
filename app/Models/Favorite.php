<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];

    const TYPE_FUND = 'fund';
    const TYPE_STOCK = 'stock';

    public static $typeMap = [
        self::TYPE_FUND  => '基金',
        self::TYPE_STOCK => '股票',
    ];
}
