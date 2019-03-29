<?php

namespace App\Model\DB\Mysql;

class MaterialCategory extends SAASModel
{
    protected $table = 'material_category';
    protected $guarded = [];
    protected $hidden = ['tax_code'];
    
}
