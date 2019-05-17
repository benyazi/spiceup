<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SceneWidget extends Model
{
    protected $table = 'scene_widgets';

    /**
     * Get the post that owns the comment.
     */
    public function scene()
    {
        return $this->belongsTo(Scene::class, 'scene_id');
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

}