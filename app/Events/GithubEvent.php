<?php
namespace App\Events\ScoreWidget;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GithubEvent implements ShouldBroadcastNow
{
    public $uuid;
    public $data;
    public $eventName;

    public function broadcastAs()
    {
        return $this->eventName;
    }

    public function __construct($eventName, $data)
    {
        $this->data = $data;
        $this->eventName = $eventName;
    }

    public function broadcastOn()
    {
        return ['scope-github'];
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