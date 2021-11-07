<?php

namespace App\Mails;

// use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $content = Content::where('identifier')->get()->first();
        return $this->subject('subject-placeholder')
            ->markdown('emails.LoginReminder')
            ->with([
                'body' => 'body-placeholder',
                'subject' => 'subject-placeholder',
            ]);
    }
}
