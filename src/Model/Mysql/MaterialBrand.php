<?php

namespace App\Model\DB\Mysql;

class MaterialBrand extends SAASModel
{
    protected $table = 'material_brand';
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->generateNameFirstChar($value);
    }

}
