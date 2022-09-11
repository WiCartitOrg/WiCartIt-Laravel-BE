<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorGeneralBusinessDetails extends Model
{
    use HasFactory;
    //hidden from json response:
    public $hidden = ['id', 'token_id', 'created_at', 'updated_at'];
    //public $visible = [];

   	//guarded from mass assignment:
    protected $guarded = ['id', 'created_at', 'updated_at'];
    //protected $fillable = [];
}
