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
    public function __construct($name, $course, $location, $url = null)
    {
        $this->setDetails($course, $location);
        $this->url = $url ?? 'https://';
        $this->subject = " eSkills4jobs - {$course}";
        $this->name = $name;
        $this->course = $course;
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
                    "venue" => "GI-KACE, Ridge - Accra",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/LPeUUYA7vanJwSmOsddnIH",
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ridge - Accra",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/IQAuUuVLBV17nRlR17izzx",
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [2, 3, 4, 5],
                        "In-Person" => [1, 6],
                    ],
                    "startDate" => "4th November, 2024",
                    "venue" => "GI-KACE, Ridge - Accra",
                    "duration" => "4 Weeks / 1 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/Itf9RIncR5O70GVs3LF4wB",
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ridge - Accra",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/HzY7RFcgain6sjbKCV2VHC",
                ],
            ],
            "Ashanti - KsTU, Main Campus" => [
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/FGPjMtIs8uDEBATZ89xUxs",
                ],
                "AI for Office Productivity" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "startDate" => "11th November, 2024",
                    "duration" => "4 Weeks / 1 month (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/L4N5ZeVOmuOEmt7J5URsOL",
                ],
                "Microsoft Artificial Intelligence" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 7],
                        "In-Person" => [3, 4, 6, 8],
                    ],
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/BmWlE5mEDDBDiXpqXJoYtU",
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Kumasi Technical University (KsTU), Main Campus - Kumasi",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/HzY7RFcgain6sjbKCV2VHC",
                ],
            ],
            "Northern" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "venue" => "Tamale Technical University (TaTU)",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/LPeUUYA7vanJwSmOsddnIH",
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Tamale Technical University (TaTU)",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/IQAuUuVLBV17nRlR17izzx",
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [2, 3, 4, 5],
                        "In-Person" => [1, 6],
                    ],
                    "venue" => "Tamale Technical University (TaTU)",
                    "startDate" => "4th November, 2024",
                    "duration" => "4 Weeks / 1 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/Itf9RIncR5O70GVs3LF4wB",
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Tamale Technical University (TaTU)",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/HzY7RFcgain6sjbKCV2VHC",
                ],
            ],
            "Central - Methodist School, Cape Coast & Elmina" => [
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/IQAuUuVLBV17nRlR17izzx",

                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [2, 3, 4, 5],
                        "In-Person" => [1, 6],
                    ],
                    "startDate" => "4th November, 2024",
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "duration" => "6 Weeks / 1.5 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/Itf9RIncR5O70GVs3LF4wB",
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/HzY7RFcgain6sjbKCV2VHC",
                ],
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "venue" => "Cape Coast Technical University (CTU), Cape Coast",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/FGPjMtIs8uDEBATZ89xUxs",
                ],
            ],
            "Upper East - GI-KACE, Ministries Enclave - Bolgatanga" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/LPeUUYA7vanJwSmOsddnIH",
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/IQAuUuVLBV17nRlR17izzx",

                ],
                "Data Science" => [
                    "mode" => [
                        "Virtual" => [3, 4, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 7, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/FGPjMtIs8uDEBATZ89xUxs",
                ],
                "Graphic Designing" => [
                    "mode" => [
                        "Virtual" => [1, 2, 3],
                        "In-Person" => [4, 5, 6],
                    ],
                    "startDate" => "28th October, 2024",
                    "venue" => "GI-KACE, Ministries Enclave - Bolgatanga",
                    "duration" => "6 Weeks / 1.5 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/BloVr6iIuiMChPWyyskwyO",
                ],
            ],
            "Eastern - Koforidua Technical University" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "venue" => "Koforidua Technical University (KTU)",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/LPeUUYA7vanJwSmOsddnIH",
                ],
                "Graphic Designing" => [
                    "mode" => [
                        "Virtual" => [1, 2, 3],
                        "In-Person" => [4, 5, 6],
                    ],
                    "startDate" => "28th October, 2024",
                    "venue" => "Koforidua Technical University (KTU)",
                    "duration" => "6 Weeks / 1.5 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/BloVr6iIuiMChPWyyskwyO",
                ],
                "Digital Marketing" => [
                    "mode" => [
                        "Virtual" => [2, 3, 4, 5],
                        "In-Person" => [1, 6],
                    ],
                    "startDate" => "4th November, 2024",
                    "venue" => "Koforidua Technical University (KTU)",
                    "duration" => "4 Weeks / 1 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/Itf9RIncR5O70GVs3LF4wB",
                ],
                "Web Technology" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "GI-KACE, Ridge - Accra",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/HzY7RFcgain6sjbKCV2VHC",
                    "resource" => "https://cloud.aiti-kace.com.gh/index.php/s/SNWPaYeHxegXYgX"
                ],
            ],
            "Bono - GI-KACE, Sunyani Penkwase Lowcost" => [
                "Data Analytics with Power BI" => [
                    "mode" => [
                        "Virtual" => [3, 4, 7, 8, 9, 11],
                        "In-Person" => [1, 2, 5, 6, 10, 12],
                    ],
                    "venue" => " GI-KACE, Sunyani Penkwase Lowcost",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/LPeUUYA7vanJwSmOsddnIH",
                ],
                "Microsoft Artificial Intelligence" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 7],
                        "In-Person" => [3, 4, 6, 8],
                    ],
                    "startDate" => "14th October, 2024",
                    "venue" => "GI-KACE, Sunyani Penkwase Lowcost",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/BmWlE5mEDDBDiXpqXJoYtU",
                ],
            ],
            "Volta - Nurses Training College, Ho" => [
                "AI for Office Productivity" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 9, 10],
                    ],
                    "venue" => "Nurses Training College, Ho",
                    "startDate" => "11th November, 2024",
                    "duration" => "4 Weeks / 1 month (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/L4N5ZeVOmuOEmt7J5URsOL",
                ],
                "Cyber Ops" => [
                    "mode" => [
                        "Virtual" => [1, 2, 5, 6, 7, 11],
                        "In-Person" => [3, 4, 8, 9, 10, 12],
                    ],
                    "venue" => "Nurses Training College, Ho",
                    "startDate" => "14th October, 2024",
                    "duration" => "8 Weeks / 2 months (Weekdays Only)",
                    "whatsapp" => "https://chat.whatsapp.com/IQAuUuVLBV17nRlR17izzx",
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
