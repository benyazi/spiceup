<?php
namespace App\Models\Github;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'github_events';

    public function getDataAttribute($value)
    {
        if(empty($value)) {
            return [];
        }
        return json_decode($value, true);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }
}