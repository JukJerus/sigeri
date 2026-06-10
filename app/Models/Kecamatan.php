<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $guarded = ['id'];

    public function kelurahan(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }
}
