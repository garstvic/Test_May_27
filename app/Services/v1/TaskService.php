<?php

namespace App\Services\v1;

use App\Priority;
use App\Status;
use App\Task;
use App\TaskPriority;
use App\TaskStatus;

class TaskService
{
    protected $_supported_includes=[
        'status'=>'task_status',
    ];
    
    protected $_clause_properties=[
        'status',
        'priority',
    ];
    
    public function getTasks($parameters)
    {
        if(empty($parameters)){
            return $this->filterTasks(Task::all());
        }

        $with_keys=$this->getWithKeys($parameters);
        $where_clauses=$this->getWhereClause($parameters);

        if (empty($where_clauses)){
            return $this->filterTasks(Task::with($with_keys)->get());
        }

        if(isset($where_clauses['status']) and isset($where_clauses['priority'])){
            return $tasks=Task::priorityandstatusare($where_clauses['status'],$where_clauses['priority'])->get();
        }
    
        if(isset($where_clauses['status'])){
            return $tasks=Task::statusis($where_clauses['status'])->get();
        }

        return $tasks=Task::statusis($where_clauses['priority'])->get();
    }
    
    public function getTask($id)
    {
        return $this->filterTasks(Task::where('id',$id)->get());
    }

    public function createTask($req)
    {
        $status=Status::where('title',$req->input('status.title'))->first();
        $priority=Priority::where('title',$req->input('priority.title'))->first();

        $task=new Task;
        $task->title=$req->input('title');
        $task->due_date=$req->input('due_date');
        $task->save();

        $task_status=new TaskStatus;
        $task_status->task_id=$task->id;
        $task_status->status_id=$status->id;
        $task_status->save();
        $task->task_status_id=$task_status->id;

        $task_priority=new TaskPriority;
        $task_priority->task_id=$task->id;
        $task_priority->priority_id=$priority->id;
        $task_priority->save();
        $task->task_priority_id=$task_priority->id;

        $task->update();

        return $this->filterTasks([$task]);
    }
    
    protected function filterTasks($tasks,$keys=[])
    {
        $data=[];

        foreach($tasks as $task){
            $entry[]=[
                'title'=>$task->title,
                'due_date'=>$task->due_date,
                'priority'=>$task->priority->title,
                'href'=>route('tasks.show',['id'=>$task->id])
                // 'href'=>route('tasks.show',['slug'=>strtolower(str_replace(' ','-',$task->title))])
            ];

            if(in_array('status',$keys)){
                $entry['status']=$task->status->title;
            }
        }

        $data[]=$entry;

        return $data;
    }

    protected function getWithKeys($parameters)
    {
        $with_keys=[];

        if(isset($parameters['include'])){

            $include_params=explode(',',$parameters['include']);
            $includes=array_intersect($this->_supported_includes,$include_params);
            $with_keys=array_keys($includes);
        }

        return $with_keys;
    }

    protected function getWhereClause($parameters)
    {
        $clause=[];

        foreach($this->_clause_properties as $prop){
            if(in_array($prop,array_keys($parameters))){
                $clause[$prop]=$parameters[$prop];
            }
        }

        return $clause;
    }
}