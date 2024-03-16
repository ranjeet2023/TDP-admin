<?php

namespace App\Traits;

use App\Models\User;
use App\Observers\RecordOwnerObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRecordOwnerProperties
{
    public static function bootHasRecordOwnerProperties()
    {
        self::observe(RecordOwnerObserver::class);
    }

    public function initializeHasRecordOwnerProperties()
    {
        $this->casts[$this->getAddedByColumn()] = 'integer';
        $this->casts[$this->getUpdatedByColumn()] = 'integer';
    }

    /**
     * @return BelongsTo
     */
    public function addedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, static::getAddedByColumn());
    }

    /**
     * @return string
     */
    public function getAddedByColumn(): string
    {
        return defined('static::ADDED_BY_USER_ID') ? static::ADDED_BY_USER_COLUMN : 'added_by';
    }

    /**
     * @return BelongsTo
     */
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, static::getUpdatedByColumn());
    }

    /**
     * @return string
     */
    public function getUpdatedByColumn(): string
    {
        return defined('static::UPDATED_BY_USER_ID') ? static::UPDATED_BY_USER_COLUMN : 'updated_by';
    }
}
