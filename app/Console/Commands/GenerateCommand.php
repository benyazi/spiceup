<?php
namespace App\Console\Commands;

use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class GenerateCommand extends Command
{
    protected $signature = 'data:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $uuid = md5(microtime());
        $screen = new Screen();
        $screen->name = 'Test '. microtime();
        $screen->uuid = $uuid;
        $screen->user_id = 1;
        $screen->save();

        $scene = new Scene();
        $scene->screen_id = $screen->id;
        $scene->name = 'Scene '.microtime();
        $scene->save();

        $scoreWidget = new SceneWidget();
        $scoreWidget->widget_type = 'score';
        $scoreWidget->scene_id = 1;
        $scoreWidget->data = [
            'score' => [
                'home' => 0,
                'away' => 0,
            ],
            'team' => [
                'home' => 'TMH',
                'away' => 'TMA',
            ],
            'color' => [
                'home' => 'red',
                'away' => 'green',
            ]
        ];
        $scoreWidget->save();
    }
}