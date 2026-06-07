<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionCompletion extends Model
{
    protected $fillable = ['submission_id', 'done_at'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
