<?php
namespace App\Services;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\GithubEvent;
use App\Models\Github\Commit;
use App\Models\Github\Events;
use App\Models\Github\Push;
use GuzzleHttp\Client;
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
        event(new GithubEvent('NewPush', [
            'pushItem' => $push->toArray()
        ]));
    }

    public function getRepos()
    {
        $url = 'https://api.github.com/orgs/hackathonbrn/repos';
        $client = new Client();
        $response = $client->get($url);
        $json = $response->getBody()->getContents();
        $data = json_decode($json, true);
        $repos = [];
        foreach ($data as $item) {
            $repos[] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }
        return $repos;
    }

    public function getCommits($repName)
    {
        $url = 'https://api.github.com/repos/hackathonbrn/'.$repName.'/commits';
        $client = new Client();
        $response = $client->get($url);
        $json = $response->getBody()->getContents();
        $data = json_decode($json, true);
        $commits = [];
        foreach ($data as $item) {
            $commits[] = [
                'commit' => $item['commit'],
                'author' => $item['author'],
                'project' => $repName,
                'sha' => $item['sha']
            ];
        }
        return $commits;
    }

    public function updateStatistic()
    {
        $repos = $this->getRepos();
        foreach ($repos as $rep)
        {
            echo 'Updating repos '.$rep['name'].PHP_EOL;
            $commits = $this->getCommits($rep['name']);
            echo 'Found commits '.count($commits).PHP_EOL;
            foreach ($commits as $commitData)
            {
                $commit = Commit::query()
                    ->where('sha', $commitData['sha'])
                    ->where('project', $rep['name'])
                    ->first();
                if(empty($commit)) {
                    $commit = new  Commit();
                    $commit->sha = $commitData['sha'];
                    $commit->project = $rep['name'];
                }
                if(isset($commitData['author']) && isset($commitData['author']['login'])) {
                    $commit->author = $commitData['author']['login'];
                } else if($commitData['commit'] && isset($commitData['commit']['author'])) {
                    $commit->author = $commitData['commit']['author']['name'];
                } else {
                    $commit->author = 'noname';
                }
                if(isset($commitData['commit']) && isset($commitData['commit']['message'])) {
                    $commit->message = $commitData['commit']['message'];
                }
                if(isset($commitData['commit']) && isset($commitData['commit']['comment_count'])) {
                    $commit->comment_count = $commitData['commit']['comment_count'];
                }
                $commit->save();
            }
        }
    }

    public function getRates()
    {

        $rates = [];
        //get rates
        $users = DB::table('github_commits')
            ->select(DB::raw('count(*) as commit_count_all, author, sum(comment_count) as comment_count_all'))
            ->where('author', '<>', 'noname')
            ->whereNotNull('author')
            ->groupBy('author')
            ->orderBy('commit_count_all', 'DESC')
            ->limit(10)
            ->get();
        $place = 1;
        foreach ($users as $user) {
            $rates[] = [
                'place' => $place,
                'sender' => $user->author,
                'count' => $user->commit_count_all,
                'comments' => $user->comment_count_all
            ];
            $place++;
        }
        return $rates;
    }
    public function updateRate()
    {
        event(new GithubEvent('UpdatedRate', [
            'rates' => $this->getRates()
        ]));
    }
}