<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'specialty', 'bio', 'is_active'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
