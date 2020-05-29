<?php

namespace App\Http\Controllers\v1;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\v1\TaskService;

class TaskController extends Controller
{
    private $_tasks;
    
    public function __construct(TaskService $service)
    {
        $this->_tasks=$service;
        
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters=request()->input();

        $data=$this->_tasks->getTasks($parameters);
        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->_tasks->validate($request->all());

        try{
            $task=$this->_tasks->createTask($request);

            return response()->json($task,201);
        }catch(Exception $e){
            return $response->json(['message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=$this->_tasks->getTask($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $task=$this->_tasks->updateTask($request,$id);

            return response()->json($task,200);
        }catch(ModelNotFoundException $ex) {
            throw $ex;
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $task=$this->_tasks->deleteTask($id);

            return response()->make('',204);
        }catch(ModelNotFoundException $ex) {
            throw $ex;
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
}
