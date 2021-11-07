<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mails\LoginReminder;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send remind emails to register users.';

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
     * "php artisan reminder:send" is used to trigger it on command line.
     *
     * @return int
     */
    public function handle()
    {
        $tempDate = env('LOGIN_REMINDER_MAIL_SEND_INTERVAL_NUM') . ' ' . env('LOGIN_REMINDER_MAIL_SEND_INTERVAL_TIME');
        $date = date('Y-m-d H:i:s', strtotime($tempDate));

        $allPendingUsers = User::where('email_verified_at', null)
            ->where('created_at', '<=', $date)
            ->get();

        foreach ($allPendingUsers as $user) {
            Mail::to($user->email)->send(new LoginReminder());
        }
    }
}
