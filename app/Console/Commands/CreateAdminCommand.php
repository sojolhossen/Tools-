<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--email= : The email address for the administrator}
                            {--name= : The name of the administrator}
                            {--password= : The password (leave empty to be prompted securely)}
                            {--force : Force creation even if an administrator already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely provision an administrator account via CLI (one-time bootstrap)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('--- OmniTools Administrator Provisioning ---');

        $existingAdminCount = User::where('is_admin', true)->count();
        if ($existingAdminCount > 0 && ! $this->option('force')) {
            $this->error("Safety Guard: {$existingAdminCount} administrator(s) already exist.");
            $this->warn('To create an additional administrator, explicitly provide the --force option.');
            return self::FAILURE;
        }

        $email = $this->option('email') ?: $this->ask('Enter administrator email address:');
        $name = $this->option('name') ?: $this->ask('Enter administrator name:', 'Platform Administrator');
        $password = $this->option('password') ?: $this->secret('Enter administrator password (min 8 chars):');

        // Validate inputs
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        // Create administrator
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make((string) $password),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        AuditLogger::log(
            action: 'admin.provisioned',
            entityType: 'User',
            entityId: $admin->id,
            details: ['email' => $email, 'name' => $name]
        );

        $this->info("Administrator [{$email}] created successfully!");
        $this->comment('Login URL: /admin/login');

        return self::SUCCESS;
    }
}
