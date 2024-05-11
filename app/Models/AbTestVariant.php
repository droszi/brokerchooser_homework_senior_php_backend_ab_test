<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbTestVariant extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function abTest(): BelongsTo
    {
        return $this->belongsTo(AbTest::class);
    }
}
