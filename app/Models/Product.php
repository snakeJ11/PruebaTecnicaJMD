<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ProductController;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost'];
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}