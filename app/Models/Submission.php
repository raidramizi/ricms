<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Type;
use App\Models\User;
use App\Models\SubmissionCompletion;
use App\Models\FileEdit;
use App\Models\HeadSubmissionEdit;


class Submission extends Model
{
   protected $fillable = [
    'staff_id',
    'user_name',
    'type_id',
    'category_id',

    'form_file',
    'form_file_name',

    'evidence_files',
    'evidence_optional',
    'reviewed_files',

    'status',

    'admin_comment',

    'verified_at',
    'rejected_at',
    'sent_to_head_at',

    'head_public_comment',
    'head_internal_comment',
    'head_reviewed_at',
];
    protected $casts = [
    'evidence_files' => 'array',
    'evidence_optional' => 'array',
    'reviewed_files' => 'array',

    'verified_at' => 'datetime',
    'rejected_at' => 'datetime',
    'sent_to_head_at' => 'datetime',
    'head_reviewed_at' => 'datetime',
];

    /* =========================================================
        RELATIONSHIPS
    ========================================================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id', 'staff_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function completion()
    {
        return $this->hasOne(SubmissionCompletion::class);
    }


    public function fileEdits()
    {
        return $this->hasMany(FileEdit::class, 'submission_id');
    }

    public function headEdits()
    {
        return $this->hasMany(HeadSubmissionEdit::class, 'submission_id');
    }
    public function approval()
{
    return $this->hasOne(SubmissionApproval::class);
}

    /* =========================================================
        FILE HELPERS
    ========================================================= */

    public function latestFile($type, $label = null)
    {
        return $this->fileEdits()
            ->where('file_type', $type)
            ->when($label, function ($q) use ($label) {
                $q->where('file_label', $label);
            })
            ->latest()
            ->first()?->path;
    }

    public function latestFileData($type, $label = null)
    {
        return $this->fileEdits()
            ->where('file_type', $type)
            ->when($label, function ($q) use ($label) {
                $q->where('file_label', $label);
            })
            ->latest()
            ->first();
    }

    public function latestMainFile()
    {
        return $this->latestFile('main') ?? $this->form_file;
    }
}
