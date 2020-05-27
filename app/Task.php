<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeStatusIs(Builder $query,$title)
    {
        return $query->whereHas('status',function($q) use ($title){
            $q->where('title',$title);
        });
    }
    
    public function scopePriorityIs(Builder $query,$title)
    {
        return $query->whereHas('priority',function($q) use ($title){
            $q->where('title',$title); 
        });
    }
    
    public function scopePriorityAndStatusAre(Builder $query,$status_title,$priority_title)
    {
        return $query->whereHas('status',function($q) use ($status_title){
            $q->where('title',$status_title);
        })->where(function($sub_query) use ($priority_title){
            $sub_query->whereHas('priority',function($q) use ($priority_title){
                $q->where('title',$priority_title); 
            });
        });
    }
}
