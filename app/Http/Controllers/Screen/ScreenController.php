<?php

namespace App\Http\Controllers\Screen;

use App\Events\ScoreWidget\ScoreChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Screen;
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

}
