<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $table = 'payment_details';
    
    //hidden from direct json response:
    public $hidden = ['id', 'bank_card_type', 'bank_card_number','bank_card_cvv', 'bank_expiry_month', 'bank_expiry_year', 'created_at', 'updated_at'];
    //public $visible = [];
 
    //guarded from direct mass assignment from request:
    protected $guarded = ['id', 'bank_card_type', 'bank_card_number', 'bank_card_cvv', 'bank_card_expiry_month', 'bank_card_expiry_year', 'created_at', 'updated_at'];
    //protected $fillable = [];
}
