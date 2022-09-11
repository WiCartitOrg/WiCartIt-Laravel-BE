<?php

namespace App\Models\Vendor;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Authenticatable //implements MustVerifyEmail//Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'vendors';

    //hidden from direct json response:
    public $hidden = ['id','unique_vendor_id', 'vendor_password', 'created_at', 'updated_at'];
    //public $visible = [];

    //guarded from direct mass assignment from request:
    protected $guarded = ['id', 'unique_vendor_id', 'vendor_password', 'created_at', 'updated_at'];
    //protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'is_logged_in' => 'bool',
        'is_email_verified' => 'bool',
        //'email_verified_at' => 'datetime',
    ];

     //Relationship:
     public function products(): HasMany
     {
        return $this->hasMany(
            $related=App\Models\General\Product::class,
            $foreignKey='unique_product_id',
            $localKey='unique_product_id'
        );
     }

}
