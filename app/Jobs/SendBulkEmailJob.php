<?php

namespace App\Jobs;

use App\Helpers\MailerHelper;
use App\Mail\GenericEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student_ids;
    public $subject;
    public $message;
    public $template;

    public $correctTemplate = false;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $data,)
    {
        $this->student_ids = $data['student_ids'];
        $this->subject = $data['subject'];
        $this->message = $data['message'];
        $this->template = $data['template'];
        $this->correctTemplate = $data['template'];

        if (
            class_exists($this->template)
            && is_subclass_of($this->template, \Illuminate\Mail\Mailable::class)
        ) {
            $this->correctTemplate = true;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // determine whether we need to send one message or multiple
        // check for '{/*/}' in the message
        $messageContainsCurlyBrackets = Str::containsAll($this->message, ['{', '}']);



        collect($this->student_ids)->chunk(200)->each(function ($ids) use ($messageContainsCurlyBrackets) {

            if (isset($this->message)) { // send raw message

                if ($messageContainsCurlyBrackets) { // variables
                    $chunckedUsers = User::whereIn('id', $ids)->get()->all();
                    foreach ($chunckedUsers as $user) {
                        $message = MailerHelper::replaceVariables($this->message, $user->toArray());
                        Mail::to($user->email)->send(
                            new GenericEmail(
                                $message,
                                $this->subject
                            )
                        );
                    }
                } else { // no variables
                    $emails = User::whereIn('id', $ids)->select('email')->pluck('email')->all();
                    Mail::to(config('mail.from.address', 'no-reply@gi-kace.gov.gh'))
                        ->bcc($emails)
                        ->send(
                            new GenericEmail(
                                $this->message,
                                $this->subject
                            )
                        );
                }
            } else if ($this->template) { // send template
                $chunckedUsers = User::whereIn('id', $ids)->get()->all();

                foreach ($chunckedUsers as $user) {
                    Mail::to($user->email)->send(
                        new $this->template($user)
                    );
                }
            }
        });
    }
}
