<?php
namespace App\Events\ScoreWidget;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CustomWidgetEventEvent implements ShouldBroadcastNow
{
    public $uuid;
    public $data;
    public $eventName;

    public function broadcastAs()
    {
        return $this->eventName;
    }

    public function __construct($uuid, $eventName, $data)
    {
        $this->uuid = $uuid;
        $this->data = $data;
        $this->eventName = $eventName;
    }

    public function broadcastOn()
    {
        return ['score-widget-'.$this->uuid];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}