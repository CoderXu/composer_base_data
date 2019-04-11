<?php

namespace App\Model\DB\Mysql;

class MaterialCategory extends CommonModel
{
    protected $table = 'material_category';
    protected $guarded = [];
    protected $hidden = ['tax_code'];
    
}
