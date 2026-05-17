<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $submissions = DB::table('dokumen_submissions')->whereNotNull('file_path')->get();

        foreach ($submissions as $submission) {
            DB::table('submission_files')->insert([
                'submission_id' => $submission->id,
                'file_path' => $submission->file_path,
                'original_name' => basename($submission->file_path),
                'created_at' => $submission->created_at,
                'updated_at' => $submission->updated_at,
            ]);
        }

        Schema::table('dokumen_submissions', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_submissions', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });

        $files = DB::table('submission_files')->get();

        foreach ($files as $file) {
            DB::table('dokumen_submissions')
                ->where('id', $file->submission_id)
                ->update(['file_path' => $file->file_path]);
        }
    }
};