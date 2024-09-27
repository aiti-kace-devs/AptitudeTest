<?php

namespace App\Mail;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamLoginCredentials extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $std, public $plainPassword)
    {
        $this->std = $std;
        $this->plainPassword = $plainPassword;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $examUrl = url('/login');

        return $this->subject('Your Exam Login Credentials')
                ->markdown('mail.exam_credentials')
                ->with([
                    'name' => $this->std->name,
                    'email' => $this->std->email,
                    'password' => $this->plainPassword,
                    'examUrl' => $examUrl
                ]);
    }
}
