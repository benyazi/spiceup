<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Events\ScoreWidget\ScorePositionChangedEvent;
use App\Events\ScoreWidget\WidgetPositionChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->where('user_id', Auth::id())
            ->get();
        return view('screen.list', [
            'list' => $list
        ]);
    }

    public function add()
    {
        $uuid = md5(microtime());
        $screen = new Screen();
        $screen->name = $uuid;
        $screen->uuid = $uuid;
        $screen->user_id =  Auth::id();
        $screen->save();
        $scene = new Scene();
        $scene->screen_id = $screen->id;
        $scene->name = 'Scene 1';
        $scene->save();
        return redirect()->route('screen.list');
    }

    public function remove(Request $request, $id)
    {
        /** @var Screen $screen */
        $screen = Screen::find($id);
        if($screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
        $screen->delete();
        return redirect()->route('screen.list');
    }

    public function edit(Request $request, $id)
    {
        $screen = Screen::find($id);
        if($screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
        return view('screen.edit', [
            'screen' => $screen
        ]);
    }

    public function addWidget(Request $request, $id, $type)
    {
        $screen = Screen::find($id);
        if($screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
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

    public function widgetSetting(Request $request, $widgetId)
    {
        $widget = SceneWidget::find($widgetId);
        if($widget->scene->screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
        $widgetData = $widget->data;
        $setting = $request->get('setting');
        $widgetData['position'] = [
            'top' => $setting['position']['top'],
            'left' => $setting['position']['left'],
        ];
        $widget->data = $widgetData;
        $widget->save();
        $screen = $widget->scene->screen;
        event(new WidgetPositionChangedEvent($screen->uuid, [
            'position' => $widgetData['position'],
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData
        ];
    }

    public function activate(Request $request, $widgetId, $value)
    {
        $widget = SceneWidget::find($widgetId);
        if($widget->scene->screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
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
