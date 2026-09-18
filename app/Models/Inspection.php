<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'address', 'type', 'status', 'dengue_breeding_site_spotted', 'requested_at', 'requested_by'])]
class Inspection extends Model
{
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
