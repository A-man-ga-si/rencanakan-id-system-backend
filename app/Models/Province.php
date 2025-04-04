<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Province extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $hidden = ['id', 'created_at', 'updated_at'];
    protected $appends = ['hashid'];

    public function itemPrice()
    {
        return $this->belongsToMany(ItemPrice::class);
    }
}
