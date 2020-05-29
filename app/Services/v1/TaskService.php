<?php

namespace App\Services\v1;

use App\Priority;
use App\Status;
use App\Task;
use App\TaskPriority;
use App\TaskStatus;
use Validator;

class TaskService
{
    protected $_supported_includes=[
        'status'=>'task_status',
    ];
    
    protected $_clause_properties=[
        'status',
        'priority',
    ];
    
    protected $rules=[
        'title'=>'required',
        'due_date'=>'required|date',
        'status.title'=>'required|task_status',
        'priority.title'=>'required|task_priority',
    ];
    
    public function validate($task)
    {
        $validator=Validator::make($task,$this->rules);
        
        $validator->validate();
    }
    
    public function getTasks($parameters)
    {
        if(empty($parameters)){
            return $this->filterTasks(Task::orderBy('id','ASC')->paginate(5));
        }

        $with_keys=$this->getWithKeys($parameters);
        $where_clauses=$this->getWhereClause($parameters);
        $order_by=$this->getOrderBy($parameters);

        if (empty($where_clauses)){
            return $this->filterTasks(Task::with($with_keys)->orderBy('id',$order_by)->paginate(5),$with_keys);
        }

        if(isset($where_clauses['status']) and isset($where_clauses['priority'])){
            return $tasks=Task::priorityandstatusare($where_clauses['status'],$where_clauses['priority'])->orderBy('id',$order_by)->paginate(5);
        }
    
        if(isset($where_clauses['status'])){
            return $tasks=Task::statusis($where_clauses['status'])->orderBy('id',$order_by)->paginate(5);
        }

        return $tasks=Task::statusis($where_clauses['priority'])->orderBy('id',$order_by)->paginate(5);
    }

    public function getTask($id)
    {
        return $this->filterTasks([Task::where('id',$id)->firstOrFail()],['status']);
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
    
    public function updateTask($req,$id)
    {
        $task=Task::where('id',$id)->firstOrFail();
        $status=Status::where('title',$req->input('status.title'))->firstOrFail();
        $priority=Priority::where('title',$req->input('priority.title'))->firstOrFail();

        $task->title=$req->input('title');
        $task->due_date=$req->input('due_date');
        $task->update();

        $task_status=TaskStatus::where('task_id',$task->id)->firstOrFail();
        $task_status->task_id=$task->id;
        $task_status->status_id=$status->id;
        $task_status->update();

        $task_priority=TaskPriority::where('task_id',$task->id)->firstOrFail();
        $task_priority->task_id=$task->id;
        $task_priority->priority_id=$priority->id;
        $task_priority->update();

        return $this->filterTasks([$task]);
    }
    
    public function deleteTask($id)
    {
        $task=Task::where('id',$id)->firstOrFail();
        $task_status=TaskStatus::where('task_id',$task->id)->firstOrFail();
        $task_priority=TaskPriority::where('task_id',$task->id)->firstOrFail();
        
        $task->delete();
        $task_priority->delete();
        $task_status->delete();
    }
    
    protected function filterTasks($tasks,$keys=[])
    {
        $data=$entry=[];

        foreach($tasks as $task){
            $entry[]=[
                'id'=>$task->id,
                'title'=>$task->title,
                'due_date'=>$task->due_date,
                'priority'=>$task->priority->title,
                'href'=>route('tasks.show',['id'=>$task->id])
                // 'href'=>route('tasks.show',['slug'=>strtolower(str_replace(' ','-',$task->title))])
            ];

            if(in_array('status',$keys)){
                $entry[count($entry)-1]['status']=$task->status->title;
            }
        }

        $data['data']=$entry;

        if(is_array($tasks) xor true){
            $data['pagination']=[
                'total'=>$tasks->total(),
                'last_page'=>$tasks->lastPage(),
                'per_page'=>$tasks->perPage(),
                'current_page'=>$tasks->currentpage(),
            ];
        }

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
    
    protected function getOrderBy($parameters)
    {
        if(isset($parameters['order'])){
            if(stripos($parameters['order'],'asc')===0 or stripos($parameters['order'],'desc')===0){
                return $parameters['order'];
            }
        }

        return 'ASC';
    }
}