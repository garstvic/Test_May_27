<?php

namespace App\Providers\v1;

use App\Services\v1\TaskService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(TasksService::class,function($app){
            return new TasksService;
        });
        
        Validator::extend('task_priority',function($attribute,$value,$parameters,$validator){
            return $value=='Low' or $value=='Normal' or $value=='High';
        });
        
        Validator::extend('task_status',function($attribute,$value,$parameters,$validator){
            return $value=='Created' or $value=='In progress' or $value=='Completed';
        });
    }
}
