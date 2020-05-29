@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">Dashboard</div>
                    <div class="float-right"><a href="{{ route('create_task') }}" class="btn btn-secondary btn-sm">Create Task</a></div>                    
                    <div class="float-right" style="margin: 0 1rem;">
                        <a href="{{ $routes['desc'] }}" class="btn btn-secondary btn-sm">
                            <svg class="bi bi-arrow-down-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path fill-rule="evenodd" d="M4.646 7.646a.5.5 0 0 1 .708 0L8 10.293l2.646-2.647a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z"/>
                              <path fill-rule="evenodd" d="M8 4.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="float-right">
                        <a href="{{ $routes['asc'] }}" class="btn btn-secondary btn-sm">
                            <svg class="bi bi-arrow-up-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path fill-rule="evenodd" d="M4.646 8.354a.5.5 0 0 0 .708 0L8 5.707l2.646 2.647a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 0 0 0 .708z"/>
                              <path fill-rule="evenodd" d="M8 11.5a.5.5 0 0 0 .5-.5V6a.5.5 0 0 0-1 0v5a.5.5 0 0 0 .5.5z"/>
                            </svg>
                        </a>
                    </div>

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!--You are logged in!-->
                    @if(empty($tasks->data))
                        <div class="d-flex justify-content-center">
                            <h4>No Tasks!</h4>
                        </div>
                    @else
                        @foreach ($tasks->data as $task)
                            <div class="card" style="margin-bottom: 5px;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $task->status }}</h6>
                                    <p class="card-text">Priority: <b>{{ $task->priority }}</b></p>
                                    <p class="card-text">Due Date: <b>{{ $task->due_date }}</b></p>
                                    <a href="{{ route('edit_task',$task->id) }}" class="card-link">Edit Task</a>
                                    <a href="{{ route('delete_task',$task->id) }}" class="card-link">Delete Task</a>
                                </div>
                            </div> 
                        @endforeach
    
                        <div class="d-flex justify-content-center" style="margin-top: 2rem;">
                            <ul role="navigation" class="pagination">
                                @if($tasks->pagination->current_page==1)
                                    <li aria-disabled="true" class="page-item disabled">
                                        <span class="page-link">« Previous</span>       
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ $routes['prev'] }}" rel="next" class="page-link">« Previous</a>
                                    </li>
                                @endif
    
                                @if($tasks->pagination->current_page>=((int) $tasks->pagination->total/$tasks->pagination->per_page))
                                    <li aria-disabled="true" class="page-item disabled">
                                        <span class="page-link">Next »</span>       
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ $routes['next'] }}" rel="next" class="page-link">Next »</a>
                                    </li>                            
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
