<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ["date"];
    protected $dateFormat = 'd-m-Y';
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
