<?php
namespace App\Events\ScoreWidget;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ScoreChangedEvent implements ShouldBroadcastNow
{
    public $uuid;
    public $data;

    public function broadcastAs()
    {
        return 'ScoreChanged';
    }

    public function __construct($uuid, $data)
    {
        $this->uuid = $uuid;
        $this->data = $data;
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