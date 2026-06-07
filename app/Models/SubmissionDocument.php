<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'type',
        'name',
        'path',
        'checked_by_head',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
