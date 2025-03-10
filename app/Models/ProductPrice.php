<?php
namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;


class ProductPrice extends Model
{
    protected $fillable = ['product_id', 'currency_id', 'price'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}