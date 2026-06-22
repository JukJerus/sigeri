<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoKerusakan extends Model
{
    protected $guarded = ['id'];

    public function kerusakan(): BelongsTo
    {
        return $this->belongsTo(Kerusakan::class);
    }
}
