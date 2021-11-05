<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mails\LoginReminder;
use Illuminate\Support\Facades\Mail;

class sendReminderEmail extends Command
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
     *
     * @return int
     */
    public function handle()
    {
        $tempDate = env('LOGIN_REMINDER_MAIL_SEND_INTERVAL_NUM') . ' ' . env('LOGIN_REMINDER_MAIL_SEND_INTERVAL_TIME');
        $date = date('Y-m-d H:i:s', strtotime($tempDate));

        $allPendingUsers = User::where('status', User::STATUS_PENDING)
            ->where('created_at', '<=', $date)
            ->get();

        foreach ($allPendingUsers as $user) {
            Mail::to($user->email)->send(new LoginReminder());
        }
    }
}
