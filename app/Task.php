<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function priority()
    {
        return $this->hasOneThrough('App\Priority','App\TaskPriority');
    }
    
    public function status()
    {
        return $this->hasOneThrough('App\Status','App\TaskStatus');
    }
}
