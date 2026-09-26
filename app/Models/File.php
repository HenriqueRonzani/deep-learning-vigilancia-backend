<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'path', 'mime_type', 'inspection_id'])]
class File extends Model
{
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function fileReports(): HasMany
    {
        return $this->hasMany(FileReport::class, 'file_id');
    }
}
