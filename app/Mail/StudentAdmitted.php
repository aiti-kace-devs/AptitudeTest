<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAdmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data = [];
    public $course;

    public $url;

    public $name;
    public $subject;

    public $courses = [];

    public $locations = [];


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name = null, $course = null, $url = null)
    {
        // $this->setDetails($course, $location);
        $this->url = $url ?? 'https://';
        $this->subject = " eSkills4jobs - {$course}";
        $this->name = $name;
        $this->course = isset($course) ? $course : Course::all()->random(1);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject ?? 'Congratulations',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.admitted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
