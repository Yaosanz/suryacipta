<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

#[Signature('cms:create-admin {--name=} {--email=} {--password= : Avoid this option in shared shell history}')]
#[Description('Create or update a secure CMS super administrator')]
class CreateCmsAdmin extends Command
{
    public function handle(): int
    {
        $name = (string) ($this->option('name') ?: $this->ask('Nama lengkap'));
        $email = mb_strtolower((string) ($this->option('email') ?: $this->ask('Email')));
        $password = (string) ($this->option('password') ?: $this->secret('Password (minimal 12 karakter)'));

        $validator = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', Password::min(12)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => UserRole::SuperAdmin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $this->info("Super admin {$email} siap digunakan. MFA wajib disiapkan saat login pertama.");

        return self::SUCCESS;
    }
}
