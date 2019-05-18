<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Events\ScoreWidget\ScorePositionChangedEvent;
use App\Events\ScoreWidget\TimerStateChangedEvent;
use App\Events\ScoreWidget\WidgetPositionChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Illuminate\Http\Request;

class TimerWidgetController extends Controller
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
    public function state(Request $request, $widgetId, $state)
    {
        $widget = SceneWidget::find($widgetId);
        $widgetData = $widget->data;
        $widgetData['state'] = $state;
        $widget->data = $widgetData;
        $widget->save();
        $screen = $widget->scene->screen;
        $timestamp = $widgetData['timestamp'];
        event(new TimerStateChangedEvent($screen->uuid, [
            'state' => $state,
            //'timestamp' => $timestamp,
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData,
            'state' => $state
        ];
    }

    public function setting(Request $request, $widgetId)
    {
        $widget = SceneWidget::find($widgetId);
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
            'widget_id' => $widget->id,
            'position' => $widgetData['position']
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData
        ];
    }
}
