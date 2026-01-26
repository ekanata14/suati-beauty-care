<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;

trait RecordActivity
{
    use LogsActivity;

    public static function bootRecordActivity()
    {
        // 1. Saat data SEDANG dibuat (Creating)
        static::creating(function ($model) {
            if (Auth::check()) {
                // Jika yang input adalah Admin/User yang sedang login
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // 2. Saat data SUDAH dibuat (Created) - KHUSUS REGISTRASI
        static::created(function ($model) {
            // Cek: Jika User belum login (Guest) DAN model ini adalah User
            if (!Auth::check() && $model instanceof User) {

                // Update kolom created_by dengan ID dia sendiri
                $model->created_by = $model->id;
                $model->updated_by = $model->id;

                // saveQuietly() PENTING agar tidak memicu event loop (infinite loop)
                $model->saveQuietly();
            }
        });

        // 3. Saat data SEDANG diupdate (Updating)
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    // ... sisa kode LogOptions dan Relasi (sama seperti sebelumnya) ...
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
