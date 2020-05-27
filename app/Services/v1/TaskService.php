<?php

namespace App\Services\v1;

use App\Task;

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
        
        $tasks=Task::priorityis('Normal')->get();
    
        return $this->filterTasks($tasks,$with_keys);
    }
    
    public function getTask($id)
    {
        return $this->filterTasks(Task::where('id',$id)->get());
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