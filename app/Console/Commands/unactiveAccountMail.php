<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\expiredAccount;
use Illuminate\Console\Command;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;

class unactiveAccountMail extends Command
{
    use Notifiable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unactiveAccountMail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email a user to activate his account';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('activation_token', '!=', '')->get();
        foreach ($users as $user) {
            $user->notify(new expiredAccount($user));
        }
    }
}
