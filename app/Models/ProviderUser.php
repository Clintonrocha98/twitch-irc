<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderUser extends Model
{
    protected $fillable = [
        'user_id',
        'provider_name',
        'provider_user_id',
        'username',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function channelMetrics(): HasMany
    {
        return $this->hasMany(ChannelUserMetric::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
