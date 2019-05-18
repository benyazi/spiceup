<?php
namespace App\Services;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\GithubEvent;
use App\Models\Github\Events;
use App\Models\Github\Push;
use Illuminate\Support\Facades\DB;

class Github
{
    public function __construct()
    {
    }

    /**
     * @param Events $event
     */
    public function createItems($event)
    {
        $data = $event->data;
        $push = new Push();
        $pusher = $sender = $project = $message = null;
        $commits = 0;
        if(isset($data['pusher']) && isset($data['pusher']['name'])) {
            $pusher = $data['pusher']['name'];
        }
        if(isset($data['sender']) && isset($data['sender']['login'])) {
            $sender = $data['sender']['login'];
        }
        if(isset($data["repository"]) && isset($data['repository']["name"])) {
            $project = $data['repository']['name'];
        }
        if(isset($data["head_commit"]) && isset($data['head_commit']["message"])) {
            $message = $data['head_commit']['message'];
        }
        if(isset($data['commits'])) {
            $commits = count($data['commits']);
        }
        $push->project = $project;
        $push->message = $message;
        $push->pusher = $pusher;
        $push->sender = $sender;
        $push->commit_count = $commits;
        //save
        $push->save();
        $rates = [];
        //get rates
        $users = DB::table('github_pushes')
            ->select(DB::raw('sum(commit_count_all) as commit_count_all, sender'))
            ->where('commit_count', '>', 0)
            ->whereNotNull('sender')
            ->groupBy('sender')
            ->orderBy('commit_count_all', 'DESC')
            ->limit(10)
            ->get();
        $place = 1;
        foreach ($users as $user) {
            $rates[] = [
                'place' => $place,
                'sender' => $user->sender,
                'count' => $user->commit_count_all
            ];
            $place++;
        }
        event(new GithubEvent('NewPush', [
            'pushItem' => $push->toArray(),
            'rates' => $rates
        ]));
    }
}