<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelUserMetric extends Model
{
    protected $fillable = [
        'provider_user_id',
        'channel',
        'total_messages',
        'xp',
        'total_watch_time',
    ];

    public function providerUser(): BelongsTo
    {
        return $this->belongsTo(ProviderUser::class);
    }
}
