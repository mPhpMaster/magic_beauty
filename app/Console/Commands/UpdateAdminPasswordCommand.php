<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:admin:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Admin Password.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password = $this->ask("Enter New Password: ");

        return User::findOrFail(config('app.support_user.id'))->update([
            'password' => Hash::make(trim($password))
        ]) != false;
    }
}
