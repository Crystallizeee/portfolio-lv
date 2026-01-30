<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class Certificate extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'name',
        'issuer',
        'year',
        'description',
        'credential_id',
        'credential_url',
        'sort_order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
