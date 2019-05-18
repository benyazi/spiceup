<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\CustomWidgetEventEvent;
use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Events\ScoreWidget\ScorePositionChangedEvent;
use App\Events\ScoreWidget\TimerStateChangedEvent;
use App\Events\ScoreWidget\WidgetPositionChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\SceneWidget;
use App\Models\Screen;
use Carbon\Carbon;
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
        if($state == 'play') {
            $curTimeStamp = Carbon::now()->timestamp;
            $newStartPoint = $curTimeStamp - $widgetData['timestamp'];
            $widgetData['startPoint'] = $newStartPoint;
        }
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

    /**
     * @param Request $request
     * @param $widgetId
     * @param $newSeconds
     * @return array
     */
    public function time(Request $request, $widgetId, $newSeconds)
    {
        $widget = SceneWidget::find($widgetId);
        $widgetData = $widget->data;
        $widgetData['timestamp'] = $newSeconds;
        if($widgetData['state'] == 'play') {
            $curTimeStamp = Carbon::now()->timestamp;
            $newStartPoint = $curTimeStamp - $widgetData['timestamp'];
            $widgetData['startPoint'] = $newStartPoint;
        }
        $widget->data = $widgetData;
        $widget->save();
        $screen = $widget->scene->screen;
        event(new CustomWidgetEventEvent($screen->uuid, 'TimerChangedTime', [
            'startPoint' => $widgetData['startPoint'],
            'timestamp' => $newSeconds,
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData,
            'startPoint' => $widgetData['startPoint'],
            'timestamp' => $newSeconds,
        ];
    }

    public function advancedSize(Request $request, $widgetId, $advancedSize)
    {
        $widget = SceneWidget::find($widgetId);
        $widgetData = $widget->data;
        $widgetData['advancedSize'] = $advancedSize;
        $widget->data = $widgetData;
        $widget->save();
        $screen = $widget->scene->screen;
        event(new CustomWidgetEventEvent($screen->uuid, 'AdvancedSizeChanged', [
            'advancedSize' => $widgetData['advancedSize'],
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
