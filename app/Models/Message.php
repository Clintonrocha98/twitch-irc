<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'provider_user_id',
        'channel',
        'message',
        'provider_message_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function providerUser(): BelongsTo
    {
        return $this->belongsTo(ProviderUser::class);
    }
}
