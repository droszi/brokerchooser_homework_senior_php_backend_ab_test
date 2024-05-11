<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbTest extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function variants(): HasMany
    {
        return $this->hasMany(AbTestVariant::class);
    }
}
