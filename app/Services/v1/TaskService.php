<?php

namespace App\Services\v1;

use App\Task;

class TaskService
{
    public function getTasks()
    {
        return $this->filterTasks(Task::all());
    }
    
    public function getTask($id)
    {
        return $this->filterTasks(Task::where('id',$id)->get());
    }
    
    protected function filterTasks($tasks)
    {
        $data=[];

        foreach($tasks as $task){
            $entry[]=[
                'title'=>$task->title,
                'due_date'=>$task->due_date,
                'status'=>$task->status->title,
                'priority'=>$task->priority->title,
                'href'=>route('tasks.show',['id'=>$task->id])
                // 'href'=>route('tasks.show',['slug'=>strtolower(str_replace(' ','-',$task->title))])
            ];
        }
        
        $data[]=$entry;
        
        return $data;
    }
}