<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\expiredAccount;
use Illuminate\Console\Command;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

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
        $users = User::where('active', false)->get();
        foreach ($users as $user) {
            $expirationDate = $user->created_at->add(3, 'day');
            if (date(now()) > $expirationDate) {
                $user->activation_token = str_random(60);
                $user->save();
                $user->notify(new expiredAccount($user));
            }
        }
        $this->info('Sent!');
    }
}
