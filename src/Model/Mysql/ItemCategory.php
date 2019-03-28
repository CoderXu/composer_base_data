<?php

namespace App\Model\DB\Mysql;

class ItemCategory extends SAASModel
{
    protected $table = 'item_category';
    protected $guarded = [];
    protected $appends = ['path'];

    public function getPathAttribute()
    {
        $path = $this->attributes['pid_path'] . ',' . $this->attributes['id'];
        return ltrim($path, ',');
    }
}
