<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('user:make-admin {email : Admin yapılacak kullanıcının email adresi}', function () {
    $email = (string) $this->argument('email');

    /** @var \App\Models\User|null $user */
    $user = User::query()->where('email', $email)->first();

    if (!$user) {
        $this->error("Kullanıcı bulunamadı: {$email}");
        return 1;
    }

    $user->is_admin = true;
    $user->save();

    $this->info("Admin yapıldı: {$user->email} (id={$user->id})");
    return 0;
})->purpose('Make a user admin');
