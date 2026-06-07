<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionApproval extends Model
{
    protected $fillable = [
        'submission_id',
        'proof_file',
        'proof_name',
        'approved_by',
        'approved_at'
    ];

    // RELATION
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
