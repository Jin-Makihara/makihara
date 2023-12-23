<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    //  販売処理
    public function sellProduct($id)
    {
        $sale = new Sale();
        $sale->product_id = $id;
        $sale->save();
    }
}
