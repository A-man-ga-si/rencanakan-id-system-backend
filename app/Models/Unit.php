<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Unit extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = ['name'];
    protected $appends = ['hashid'];
    // protected $hidden = ['id'];

    public function itemPrice()
    {
        return $this->hasMany(ItemPrice::class);
    }
}
