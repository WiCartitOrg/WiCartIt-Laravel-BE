<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerBilling extends Model
{
    use HasFactory;

    protected $table = 'buyer_billings';
}
