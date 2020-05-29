<form method="POST" action="{{ route('update_task',[$task->id]) }}">
    @csrf

    <div class="form-group row">
        
        <label for="title" class="col-md-4 col-form-label text-md-right">Task Title</label>

        <div class="col-md-6">
            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $task->title }}" required autocomplete="title" autofocus>
            
            @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Task Status') }}</label>

        <div class="col-md-6">
            <select id="status" class="form-control custom-select @error('status.title') is-invalid @enderror" name="status[title]" required>
                @foreach($statuses as $status)
                    <option value="{{ $status->title }}" {{ $task->status==$status->title ? 'selected' : '' }}>{{ $status->title }}</option>
                @endforeach
            </select>

            @error('status.title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    
    <div class="form-group row">
        <label for="due_date" class="col-md-4 col-form-label text-md-right">{{ __('Task Due Date') }}</label>

        <div class="col-md-6">
            <input id="due_date" type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ mb_substr($task->due_date,0,10) }}" required>

            @error('due_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    
    <div class="form-group row">
        <label for="priority" class="col-md-4 col-form-label text-md-right">{{ __('Task Priority') }}</label>

        <div class="col-md-6">
            <select id="priority" class="form-control custom-select @error('priority.title') is-invalid @enderror" name="priority[title]" required>
                @foreach($priorities as $priority)
                    <option value="{{ $priority->title }}" {{ $task->priority==$priority->title ? 'selected' : '' }}>{{ $priority->title }}</option>
                @endforeach
            </select>            

            @error('priority.title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Update') }}
            </button>
        </div>
    </div>
</form>