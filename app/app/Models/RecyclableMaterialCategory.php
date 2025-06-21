<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecyclableMaterialCategory extends Model
{
    //
    protected $table = 'recyclable_material_category';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name','soft_delete',
    ];
}
