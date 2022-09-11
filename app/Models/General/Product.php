<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    //hidden from direct json response:
    public $hidden = ['id', 'created_at', 'updated_at'];
    //public $visible = [];

   	//guarded from direct mass assignment from request:
    protected $guarded = ['id','created_at', 'updated_at'];
    //protected $fillable = [];

    public function vendor(): HasOne
    {
        return $this->hasOne(
            $related=App\Models\General\Vendor::class, 
            $foreignKey='unique_vendor_id',
            $localKey='unique_vendor_id'
        );
    }
}
