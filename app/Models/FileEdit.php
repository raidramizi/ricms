<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

Class FileEdit extends Model
{
    protected $table = 'file_edits';

    protected $fillable = [
        'submission_id',
        'file_type',
        'file_label',
        'original_name',
        'path',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }
}
