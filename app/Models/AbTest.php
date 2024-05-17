<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbTest extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const STATUS_READY_TO_RUN = 'ready_to_run';
    public const STATUS_RUNNING = 'running';
    public const STATUS_STOP = 'stop';

    public function variants(): HasMany
    {
        return $this->hasMany(AbTestVariant::class);
    }

    public function scopeRunning(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_RUNNING);
    }

    protected function getIsRunningAttribute(): bool
    {
        return $this->status === self::STATUS_RUNNING;
    }

    protected function getIsReadyToRunAttribute(): bool
    {
        return $this->status === self::STATUS_READY_TO_RUN;
    }
}
