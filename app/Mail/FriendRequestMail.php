<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FriendRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sender;

    public function __construct(User $sender)
    {
        $this->sender = $sender;
    }

    public function build()
    {
        return $this->subject('New Friend Request')
            ->view('emails.friend_request');
    }
}
