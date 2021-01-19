<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Automatic extends Model
{
    use HasDateTimeFormatter;

    const TYPE_WEEK = 1;
    const TYPE_FORTNIGHT = 2;
    const TYPE_MONTH = 3;
    const TYPE_DAY = 4;

    public static $typeMap = [
        self::TYPE_WEEK      => '每周',
        self::TYPE_FORTNIGHT => '每两周',
        self::TYPE_MONTH     => '每月',
        self::TYPE_DAY       => '每日',
    ];

    public static $dayMap = [
        self::TYPE_WEEK      => [
            0 => '周日',
            1 => '周一',
            2 => '周二',
            3 => '周三',
            4 => '周四',
            5 => '周五',
            6 => '周六',
        ],
        self::TYPE_FORTNIGHT => [
            0 => '周日',
            1 => '周一',
            2 => '周二',
            3 => '周三',
            4 => '周四',
            5 => '周五',
            6 => '周六',
        ],
        self::TYPE_MONTH     => [
            1  => '1日',
            2  => '2日',
            3  => '3日',
            4  => '4日',
            5  => '5日',
            6  => '6日',
            7  => '7日',
            8  => '8日',
            9  => '9日',
            10 => '10日',
            11 => '11日',
            12 => '12日',
            13 => '13日',
            14 => '14日',
            15 => '15日',
            16 => '16日',
            17 => '17日',
            18 => '18日',
            19 => '19日',
            20 => '20日',
            21 => '21日',
            22 => '22日',
            23 => '23日',
            24 => '24日',
            25 => '25日',
            26 => '26日',
            27 => '27日',
            28 => '28日',
        ],
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
