<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    protected $fillable = [
        'name', 'specialty', 'bio', 'is_active'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
