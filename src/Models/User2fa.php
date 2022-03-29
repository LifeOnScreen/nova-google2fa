<?php

namespace Lifeonscreen\Google2fa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User2fa
 * @package Lifeonscreen\Google2fa\Models
 */
class User2fa extends Model
{
    /**
     * @var string
     */
    protected $table   = 'user_2fa';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('lifeonscreen2fa.models.user'),config('lifeonscreen2fa.tables.foreign'));
    }
}
