<?php

// app/Models/ImportExportLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportExportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'operation_type',
        'status',
        'result_message',
        'records_processed',
        'records_success',
        'records_failed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}