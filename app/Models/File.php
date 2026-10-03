<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'path', 'mime_type', 'inspection_id'])]
class File extends Model
{
    use HasFactory;
    protected $appends = ['url'];

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => \Storage::disk('s3')->url($this->path)
        );
    }
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function fileReports(): HasMany
    {
        return $this->hasMany(FileReport::class, 'file_id');
    }
}
