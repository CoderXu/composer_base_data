<?php

namespace App\Model\DB\Mysql;

class MaterialCategory extends CommonModel
{
    protected $table = 'material_category';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $this->hidden[] = 'tax_code';
        parent::__construct($attributes);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->generateNameFirstChar($value);
    }
}
