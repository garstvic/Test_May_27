<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function priority()
    {
        return $this->hasOneThrough(
            'App\Priority',
            'App\TaskPriority',
            'task_id',
            'id',
            'task_priority_id',
            'priority_id'
        );
    }
    
    public function status()
    {
        return $this->hasOneThrough(
            'App\Status',
            'App\TaskStatus',
            'task_id',
            'id',
            'task_status_id',
            'status_id'
        );
    }
}
