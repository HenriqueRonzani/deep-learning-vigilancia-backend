<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['file_id', 'irregularity', 'status', 'agent_report', 'user_feedback', 'feedback_by'])]
class FileReport extends Model
{
    use HasFactory;

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function feedbackUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
