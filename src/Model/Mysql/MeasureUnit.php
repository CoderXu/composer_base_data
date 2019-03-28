<?php

namespace App\Model\DB\Mysql;

class MeasureUnit extends SAASModel
{
    protected $table = 'measure_unit';
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->generateNameFirstChar($value);
    }
}
