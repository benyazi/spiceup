<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    protected $table = 'scenes';

    public function screen()
    {
        return $this->belongsTo(Screen::class, 'screen_id');
    }
}