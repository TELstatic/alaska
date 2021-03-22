<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasDateTimeFormatter;

    const TYPE_BUY = 1;
    const TYPE_SOLD = 2;
    const TYPE_AUTO = 3;
    const TYPE_DIVIDEND = 4;

    public static $typeMap = [
        self::TYPE_BUY      => '买入',
        self::TYPE_SOLD     => '卖出',
        self::TYPE_AUTO     => '定投',
        self::TYPE_DIVIDEND => '分红',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
