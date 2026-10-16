<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LeadDocument extends Model
{
    protected $fillable = ['lead_id', 'uploaded_by', 'label', 'original_name', 'file_path'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** A safe file name for downloads, built from the label the rep gave it. */
    public function downloadName(): string
    {
        $base = Str::slug($this->label) ?: 'document-'.$this->id;
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) ?: 'pdf';

        return $base.'.'.$extension;
    }
}
