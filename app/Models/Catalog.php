<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasDateTimeFormatter;

    protected $guarded = [];

    const RISK_LEVEL_R1 = 1;
    const RISK_LEVEL_R2 = 2;
    const RISK_LEVEL_R3 = 3;
    const RISK_LEVEL_R4 = 4;
    const RISK_LEVEL_R5 = 5;

    //R1(谨慎型)该级别理财产品保本保收益，风险很低
    //R2(稳健型)该级别理财产品不保本，风险相对较小
    //R3(平衡型)该级别理财产品不保本，风险适中
    //R4(进取型)该级别理财产品不保本，风险较大
    //R5(激进型)该级别理财产品不保本，风险极大

    public static $riskLevelMap = [
        self::RISK_LEVEL_R1 => 'R1-谨慎型',
        self::RISK_LEVEL_R2 => 'R2-稳健型',
        self::RISK_LEVEL_R3 => 'R3-平衡型',
        self::RISK_LEVEL_R4 => 'R4-进取型',
        self::RISK_LEVEL_R5 => 'R5-激进型',
    ];

    public function projects(){
        return $this->hasMany(Project::class);
    }
}
