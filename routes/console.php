<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use Carbon\Carbon;

// kalau mau schedule buat hapus user yang belum verifikasi email
// ≥5 menit
// maka bisa pakai kode ini
// php artisan schedule:work | untuk menjalankan aksinya
Schedule::command('users:delete-unverified')->everyMinute();

Schedule::call(function () {
    $timeLimit = Carbon::now()->subMinutes(30);

    $deletedCount = User::whereNull('email_verified_at')
        ->where('created_at', '<', $timeLimit)
        ->delete();

    logger("Deleted $deletedCount unverified users at " . now());
})->everyMinute();
