<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadSubmissionEdit extends Model
{
    protected $fillable = [
    'submission_id',
    'head_id',
    'file_type',
    'file_key',
    'old_file',
    'new_file',
];
}
