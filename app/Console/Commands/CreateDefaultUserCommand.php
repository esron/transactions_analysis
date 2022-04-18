<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDefaultUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:default-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the default user with username Admin and 12399 passwd';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@email.com.br',
            'password' => Hash::make('123999'),
        ]);

        $this->info("User $user->name created");

        return 0;
    }
}
