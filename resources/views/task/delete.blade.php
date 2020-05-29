@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Delete Task') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('destroy_task',[$task->id]) }}">  
                        @csrf
                        
                        <div class="card" style="margin-bottom: 5px;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $task->status }}</h6>
                                <p class="card-text">Priority: <b>{{ $task->priority }}</b></p>
                                <p class="card-text">Due Date: <b>{{ $task->due_date }}</b></p>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Destroy') }}
                                </button>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection