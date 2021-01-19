<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasDateTimeFormatter;

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
