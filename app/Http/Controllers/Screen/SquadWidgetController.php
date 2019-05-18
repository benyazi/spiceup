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
use Illuminate\Http\Request;

class SquadWidgetController extends Controller
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
    public function squad(Request $request, $widgetId)
    {
        $widget = SceneWidget::find($widgetId);
        $widgetData = $widget->data;
        $squad = $request->get('squad');
        $widgetData = array_merge($widgetData, $squad);
        $screen = $widget->scene->screen;
        $widget->data = $widgetData;
        $widget->save();
        event(new CustomWidgetEventEvent($screen->uuid, 'UpdateSquad', [
            'squad' => $squad,
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
            'position' => $widgetData['position'],
            'widget_id' => $widget->id
        ]));
        return [
            'success' => true,
            'widgetData' => $widgetData
        ];
    }
}
