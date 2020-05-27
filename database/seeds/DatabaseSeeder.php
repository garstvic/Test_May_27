<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach(['Low','Normal','High'] as $priority) {
            DB::table('priorities')->insert([
                'title'=>$priority,
                'created_at'=>date('Y-m-d H:m:s'),
                'updated_at'=>date('Y-m-d H:m:s'),
            ]);
        }

        foreach(['Created','In progress','Completed'] as $status) {
            DB::table('statuses')->insert([
                'title'=>$status,
                'created_at'=>date('Y-m-d H:m:s'),
                'updated_at'=>date('Y-m-d H:m:s'),
            ]);
        }

        factory(App\Task::class,300)->make()->each(function($task){
            $task->save();
            factory(App\TaskStatus::class,1)->make()->each(function($status) use ($task){
                $status->task_id=$task->id;
                $status->save();

                $task->task_status_id=$status->id;
            });

            factory(App\TaskPriority::class,1)->make()->each(function($priority) use ($task){
                $priority->task_id=$task->id;
                $priority->save();

                $task->task_priority_id=$priority->id;
            });

            $task->save();
        });
    }
}
