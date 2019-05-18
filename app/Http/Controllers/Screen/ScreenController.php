<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List of screens
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list()
    {
        $list = Screen::query()
            ->get();
        return view('screen.list', [
            'list' => $list
        ]);
    }

    public function add()
    {
        return view('screen.add');
    }

    public function remove(Request $request, $id)
    {
        return view('screen.remove');
    }

    public function edit(Request $request, $id)
    {
        $screen = Screen::find($id);
        return view('screen.edit', [
            'screen' => $screen
        ]);
    }

    public function addWidget(Request $request, $id, $type)
    {
        $screen = Screen::find($id);
        $scene = Scene::query()
            ->where('screen_id', $id)
            ->first();
        $widget = new SceneWidget();
        $widget->scene_id = $scene->id;
        $widget->widget_type = $type;
        $widget->setDefaultData();
        $widget->save();
        event(new CustomWidgetEventEvent($screen->uuid, 'AddedNewWidget', [
            'newWidget' => $widget->toArray()
        ]));
        return [
            'success' => true,
            'newWidget' => $widget->toArray()
        ];
    }

    public function activate(Request $request, $widgetId, $value)
    {
        $widget = SceneWidget::find($widgetId);
        $value = ($value == 'true')?true:false;
        $widget->is_active = (boolean) $value;
        $widget->save();
        $screen = $widget->scene->screen;
        event(new CustomWidgetEventEvent($screen->uuid, 'WidgetActivateChanged', [
            'value' => (boolean) $value,
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
        ];
    }

}
