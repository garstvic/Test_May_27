<?php

namespace App\Http\Controllers;

use App\Task;
use App\Status;
use App\Priority;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TaskController extends Controller
{
    protected $_client;
    
    public function __construct()
    {
        $this->_client=new Client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses=Status::all();
        $priorities=Priority::all();
        
        return view('task.create',[
            'statuses'=>$statuses,
            'priorities'=>$priorities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data=$request->validate([
            'title'=>'required|max:255',
            'due_date'=>'required|date',
            'priority.title'=>'required|task_priority',
            'status.title'=>'required|task_status',
        ]);

        $user_token=$request->session()->get('user');

        $response=$this->_client->request('POST','http://localhost/api/v1/tasks',[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ],
            'json'=>$validated_data
        ]);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_token=request()->session()->get('user');        
        
        $response=$this->_client->request('GET','http://localhost/api/v1/tasks/'.$id,[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ],
        ]);        

        $task=null;
        
        if($response->getStatusCode()=='200'){
            $data=json_decode($response->getBody());

            if(isset($data->data) and (empty($data->data) xor true)){
                $task=array_shift($data->data);
            }
        }
        
        return view('task.delete',['task'=>$task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $statuses=Status::all();
        $priorities=Priority::all();
        
        $user_token=request()->session()->get('user');        
        
        $response=$this->_client->request('GET','http://localhost/api/v1/tasks/'.$id,[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ],
        ]);        

        $task=null;
        
        if($response->getStatusCode()=='200'){
            $data=json_decode($response->getBody());

            if(isset($data->data) and (empty($data->data) xor true)){
                $task=array_shift($data->data);
            }
        }

        return view('task.edit',[
            'task'=>$task,
            'statuses'=>$statuses,
            'priorities'=>$priorities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated_data=$request->validate([
            'title'=>'required|max:255',
            'due_date'=>'required|date',
            'priority.title'=>'required|task_priority',
            'status.title'=>'required|task_status',
        ]);

        $user_token=$request->session()->get('user');

        $response=$this->_client->request('PUT','http://localhost/api/v1/tasks/'.$id,[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ],
            'json'=>$validated_data
        ]);

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user_token=request()->session()->get('user');        
        
        $response=$this->_client->request('DELETE','http://localhost/api/v1/tasks/'.$id,[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ],
        ]);
        
        return redirect()->route('home');
    }
}
