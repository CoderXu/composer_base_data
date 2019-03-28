<?php

namespace App\Model\DB\Mysql;

class Item extends SAASModel
{
    protected $table = 'item';
    protected $guarded = [];

    public function itemCategory()
    {
        return $this->hasOne('App\Model\DB\Mysql\ItemCategory', 'id', 'item_category_id');
    }
}
