<?php

// 格式化数字
function formatNumber($number, $precision = 0)
{
    if ($precision) {
        return round($number, $precision);
    }

    return floatval($number);
}

// 格式化百分比
function formatRate($rate)
{
    return floatval($rate) * 100 .'%';
}

/**
 * 等额本金贷款
 * @desc 等额本金贷款
 * @param int $month 贷款月数
 * @param float $rate 贷款利率
 * @param float $total 贷款总额
 * @return array
 * @author TELstatic
 * Date: 2021/1/14/0014
 */
function loanPrincipal($month, $rate, $total)
{
    if ($month == 0) {
        return [
            '本金'    => 0,
            '总利息'   => 0,
            '本息合计'  => 0,
            'items' => [],
            'total' => 0,
        ];
    }

    $principal = $total / $month; //每个月还款本金
    $rate = $rate / 100;
    $totalInterest = 0; //总利息

    $data = [
        '本金'    => $total,
        '总利息'   => 0,
        '本息合计'  => $total,
        'items' => [],
        'total' => $month,
    ];

    $now = now();

    for ($i = 0; $i < $month; $i++) {
        $interest = $total * $rate / 12; //每月还款利息

        $data['items'][] = [
            '期数'   => '第'.($i + 1).'期',
            '本金'   => $principal,
            '利息'   => $interest,
            '本息小计' => $principal + $interest,
            '还款日期' => $now->toDateString(),
        ];

        $now = $now->addMonth();

        $total -= $principal;

        $totalInterest = $totalInterest + $interest;
    }

    $data['总利息'] = $totalInterest;
    $data['本息合计'] += $totalInterest;

    return $data;
}

/**
 * 等额本息贷款
 * @desc 等额本息贷款
 * @param int $month 贷款月数
 * @param float $rate 贷款利率
 * @param int $total 贷款总额
 * @return array
 * @author TELstatic
 * Date: 2021/1/18/0018
 */
function loanInterest($month, $rate, $total)
{
    if ($month == 0) {
        return [
            '本金'    => 0,
            '总利息'   => 0,
            '本息合计'  => 0,
            'items' => [],
            'total' => 0,
        ];
    }

    $data = [
        '本金'    => $total,
        '总利息'   => 0,
        '本息合计'  => $total,
        'items' => [],
        'total' => $month,
    ];

    $now = now();

    $rate = $rate / 100;

    //每月还款金额
    $perPrincipal = $total * $rate / 12 * pow(1 + $rate / 12, $month) / (pow(1 + $rate / 12, $month) - 1);

    $totalInterest = 0; //总利息

    for ($i = 0; $i < $month; $i++) {
        $interest = $total * $rate / 12;  //每月还款利息

        $principal = $perPrincipal - $interest; //每月还款本金

        $data['items'][] = [
            '期数'   => '第'.($i + 1).'期',
            '本金'   => $principal,
            '利息'   => $interest,
            '本息小计' => $principal + $interest,
            '还款日期' => $now->toDateString(),
        ];

        $now = $now->addMonth();

        $total = $total - $principal;

        $totalInterest += $interest;
    }

    $data['总利息'] = $totalInterest;
    $data['本息合计'] += $totalInterest;

    return $data;
}

/**
 * 复利计算器
 * @desc 一次性支付现值计算
 * @author TELstatic
 * Date: 2021/1/18/0018
 * 复利计算器公式
 * P=F*(1+i)^-n
 */
function calcPresentValue($futureValue, $rate, $years)
{
    if ($years == 0) {
        return [
            '本金'    => 0,
            '总利息'   => 0,
            '本息合计'  => 0,
            'items' => [],
            'total' => 0,
        ];
    }

    $data = [
        '本金'    => 0,
        '总利息'   => 0,
        '本息合计'  => $futureValue,
        'items' => [],
        'total' => $years,
    ];

    $totalInterest = 0;

    for ($i = $years; $i > 0; $i--) {
        $presentValue = $futureValue / (1 + $rate);

        $interest = $futureValue - $presentValue;

        $data['items'][$i] = [
            '年份'   => '第'.($i).'年',
            '本金'   => $presentValue,
            '利息'   => $interest,
            '本息小计' => $futureValue,
        ];

        $totalInterest += $interest;

        $futureValue = $presentValue;
    }

    ksort($data['items']);

    $data['总利息'] = $totalInterest;
    $data['本金'] = $data['本息合计'] - $totalInterest;
//    $data['a'] = $futureValue * pow((1 + $rate), -$years);

    return $data;
}

/**
 * 复利计算器
 * @desc 一次性支付终值计算
 * @author TELstatic
 * Date: 2021/1/18/0018
 * 复利计算器公式
 * F=P*(1+i)^n
 */
function calcFutureValue($presentValue, $rate, $years)
{
    if ($years == 0) {
        return [
            '本金'    => 0,
            '总利息'   => 0,
            '本息合计'  => 0,
            'items' => [],
            'total' => 0,
        ];
    }

    $featureValue = $presentValue;

    $data = [
        '本金'    => $featureValue,
        '总利息'   => 0,
        '本息合计'  => $featureValue,
        'items' => [],
        'total' => $years,
    ];

    $totalInterest = 0;

    for ($i = 0; $i < $years; $i++) {
        $interest = $featureValue * $rate;

        $data['items'][] = [
            '年份'   => '第'.($i + 1).'年',
            '本金'   => $featureValue,
            '利息'   => $interest,
            '本息小计' => $featureValue + $interest,
        ];

        $totalInterest += $interest;

        $featureValue += $interest;
    }

    $data['总利息'] = $totalInterest;
    $data['本息合计'] += $totalInterest;

    return $data;
}
