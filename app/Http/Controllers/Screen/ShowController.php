<?php

namespace App\Http\Controllers\Screen;

use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * List of screens
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view(Request $request, $uuid)
    {
        $screen = Screen::query()
            ->where('uuid', $uuid)
            ->first();
        return view('screen.view', [
            'uuid' => $uuid
        ]);
    }

    public function data(Request $request, $uuid)
    {
        $screen = Screen::query()
            ->where('uuid', $uuid)
            ->first();
        $scene = Scene::query()
            ->where('screen_id', $screen->id)
            ->first();
        $widgets = SceneWidget::query()
            ->where('scene_id', $scene->id)
            ->get();
        $widgetArray = [];
        foreach ($widgets as $widget) {
            $widgetArray[$widget->id] = [
                'id' => $widget->id,
                'widgetData' => $widget->data,
                'data' => $widget->data,
                'type' => $widget->widget_type
            ];
        }
        return [
            'id' => $screen->id,
            'name' => $screen->name,
            'uuid' => $screen->uuid,
            'widgets' => $widgetArray
        ];
    }
}
