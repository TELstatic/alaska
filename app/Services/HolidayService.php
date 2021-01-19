<?php

namespace App\Services;

use Carbon\Carbon;

class HolidayService
{
    const TYPE_WEEKDAY = 1;
    const TYPE_OFF_DAY = 2;
    const TYPE_WEEKEND = 3;
    const TYPE_HOLIDAY = 4;

    public static $typeMap = [
        self::TYPE_WEEKDAY => '工作日',
        self::TYPE_OFF_DAY => '调休日',
        self::TYPE_WEEKEND => '周末',
        self::TYPE_HOLIDAY => '节假日',
    ];

    public $date;

    public $data;

    public function __construct($date)
    {
        $this->date = Carbon::parse($date);

        if ($this->date->year != date('Y')) {
            throw new \Exception('仅支持当前年份');
        }

        $file = storage_path('date/'.$this->date->year.'.json');

        $this->data = json_decode(file_get_contents($file), true);
    }

    public function getData()
    {
        return $this->data;
    }

    public function isWeekend()
    {
        return Carbon::parse($this->date)->isWeekend();
    }

    public function isWeekday()
    {
        return Carbon::parse($this->date)->isWeekday();
    }

    public function isOffDay()
    {
        $index = Carbon::parse($this->date)->format('md');

        if (isset($this->data[$index])) {
            return $this->data[$index] === 0;
        }

        return false;
    }

    public function isHoliday()
    {
        $index = Carbon::parse($this->date)->format('md');

        if (isset($this->data[$index])) {
            return $this->data[$index] === 1 || $this->data[$index] === 2;
        }

        return false;
    }
}
