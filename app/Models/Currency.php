<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;


class Currency extends Model
{
    protected $fillable = ['name', 'symbol', 'exchange_rate'];
}