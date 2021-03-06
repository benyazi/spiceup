<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Events\ScoreWidget\ScorePositionChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreWidgetController extends Controller
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
     */
    public function change(Request $request, $widgetId, $team, $type)
    {
        $widget = SceneWidget::find($widgetId);
        if($widget->scene->screen->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому экрану');
        }
        $widgetData = $widget->data;
        $score = $widgetData['score'][$team];
        if($type == 'add') {
            $score++;
        } else {
            $score--;
        }
        $widgetData['score'][$team] = $score;
        $screen = $widget->scene->screen;
        $widget->data = $widgetData;
        $widget->save();
        event(new ScoreChangedEvent($screen->uuid, [
            'team' => $team,
            'score' => $score,
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData
        ];
    }
    public function setting(Request $request, $widgetId)
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
        if(isset($setting['teams'])) {
            $widgetData['team'] = $setting['teams']['team'];
            $widgetData['color'] = $setting['teams']['color'];
        }
        $widget->data = $widgetData;
        $widget->save();
        $screen = $widget->scene->screen;
        event(new ScorePositionChangedEvent($screen->uuid, [
            'position' => $widgetData['position'],
            'widget_id' => $widget->id
        ]));
        event(new CustomWidgetEventEvent($screen->uuid, 'UpdateTeamData', [
            'data' => $widgetData,
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData
        ];
    }
}
