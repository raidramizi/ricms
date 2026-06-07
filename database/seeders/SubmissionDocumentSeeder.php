<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\SubmissionDocument;

class SubmissionDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $submissions = Submission::all();

        foreach ($submissions as $s) {

            $files = json_decode($s->evidence_files, true);

            if (is_array($files)) {
                foreach ($files as $key => $file) {

                    SubmissionDocument::create([
                        'submission_id' => $s->id,
                        'type' => 'evidence',
                        'name' => $file['original_name'] ?? 'File',
                        'path' => $file['path'] ?? '',
                        'checked_by_head' => false,
                    ]);
                }
            }
        }
    }
}
