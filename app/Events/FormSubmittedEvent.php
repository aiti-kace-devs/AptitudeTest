<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormSubmittedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $submissionData;

    /**
     * Create a new event instance.
     */
    public function __construct(mixed $submissionData, $fieldName)
    {
        $this->submissionData = $submissionData;
        $this->submissionData['phone_number_field'] = $fieldName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
