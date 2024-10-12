<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAdmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
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
    public function __construct($name, $course, $location, $url)
    {
        $this->setDetails($course, $location);
        $this->url = $url ?? 'https://';
        $this->subject = " eSkills4jobs - {$course}";
        $this->name = $name;
        $this->course = $course;
        $this->courses = [
            "Data Analytics with Power BI" => [
                "mode" => [
                    "Virtual" => [3, 4, 7, 8, 9, 11],
                    "In-Person" => [1, 2, 5, 6, 10, 12],
                ],
            ],
            "Cyber Ops" => [
                "mode" => [
                    "Virtual" => [1, 2, 5, 6, 7, 11],
                    "In-Person" => [3, 4, 9, 10],
                ],
            ],
            "Digital Marketing" => [
                "mode" => [
                    "Virtual" => [1, 2, 5, 6, 7, 11],
                    "In-Person" => [3, 4, 9, 10],
                ],
                "startDate" => "14th October, 2024",
            ],
            "Web Technology" => [
                "mode" => [
                    "Virtual" => [1, 2, 5, 6, 7, 11],
                    "In-Person" => [3, 4, 8, 9, 10, 12],
                ],
            ],
            "Data Science" => [
                "mode" => [
                    "Virtual" => [3, 4, 8, 9, 11],
                    "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                ],
            ],
            "AI for Office Productivity" => [
                "mode" => [
                    "Virtual" => [1, 2, 5, 6, 7, 11],
                    "In-Person" => [3, 4, 9, 10],
                ],
            ],
            "Microsoft Artificial Intelligence" => [
                "mode" => [
                    "Virtual" => [1, 2, 5, 7],
                    "In-Person" => [3, 4, 6, 8],
                ],
            ],
        ];

        $this->locations = [
            "Greater Accra - GI-KACE, Ridge" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Ashanti - KsTU, Main Campus" => [
                "startDate" => "14th October, 2024",
                "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Northern" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Upper East - GI-KACE, Ministries Enclave - Bolgatanga" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Eastern - Koforidua Technical University" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Bono - GI-KACE, Sunyani Lowcost" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Bono East" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Volta - Nurses Training College, Ho" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Central - Methodist School, Cape Coast & Elmina" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
            "Western North - Juaboso Senior High School" => [
                "startDate" => "14th October, 2024",
                "venue" => "Tamale Technical University (TaTU)",
                "duration" => "12 Weeks / 3 months (Weekdays Only)"
            ],
        ];
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

    public function setDetails($course, $location)
    {
        $data = [
            "Greater Accra - GI-KACE, Ridge" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ridge - Accra",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ridge - Accra",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ridge - Accra",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ridge - Accra",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Ashanti - KsTU, Main Campus" => [
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "AI for Office Productivity" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "startDate" => "11th November, 2024",
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "duration" => "4 Weeks / 1 month (Weekdays Only)"
                ],
                "Microsoft Artificial Intelligence" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 7],
                        "In-Person" => [3, 4, 6, 8],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)"
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Northern" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Tamale Technical University (TaTU)",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Tamale Technical University (TaTU)",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Tamale Technical University (TaTU)",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Tamale Technical University (TaTU)",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Central - Methodist School, Cape Coast & Elmina" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Upper East - GI-KACE, Ministries Enclave - Bolgatanga" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Eastern - Koforidua Technical University" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Koforidua Technical University (KTU)",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],
            "Bono - GI-KACE, Sunyani Penkwase Lowcost" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => " GI-KACE, Sunyani Penkwase Lowcost",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
                "Microsoft Artificial Intelligence" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 7],
                        "In-Person" => [3, 4, 6, 8],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Sunyani Penkwase Lowcost",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)"
                ],
            ],
            "Volta - Nurses Training College, Ho" => [
                "AI for Office Productivity" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "startDate" => "11th November, 2024",
                    "venue" => "Nurses Training College, Ho",
                    "duration" => "4 Weeks / 1 month (Weekdays Only)"
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "Nurses Training College, Ho",
                    "duration" => "12 Weeks / 3 months (Weekdays Only)"
                ],
            ],

        ];

        $this->data = $data[$location][$course];
        $mode = $this->data['mode'];
        $mode = collect($mode);
        $keyed = collect($mode)->map(function ($item, $key) {
            return collect($item)->map(function ($week) use ($key) {
                return ['Week ' . $week => $key];
            });
        });

        $merged = collect($keyed->get('Virtual'))->merge($keyed->get('In-Person'))->values()->collapse()->all();

        $sorted = collect($merged)->sortBy(function ($mode, $key) {
            return (int)(substr($key, -2));
        });

        $this->data['modes'] = $sorted->all();
    }
}
