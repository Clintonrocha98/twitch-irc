<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'provider_user_id',
        'channel',
        'message',
        'xp',
        'provider_message_id',
        'sent_at',
    ];

    /**
     * @return BelongsTo<ProviderUser, $this>
     */
    public function providerUser(): BelongsTo
    {
        return $this->belongsTo(ProviderUser::class);
    }

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }
}
