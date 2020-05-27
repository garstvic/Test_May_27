<?php

namespace App\Services\v1;

use App\Task;

class TaskService
{
    public function getTasks()
    {
        return Task::all();
    }
}