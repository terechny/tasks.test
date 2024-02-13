<?php


namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskService
{

    public function index(Request $data){
        
        $sort = $data->column;
        $direction = $data->direction;

        return Task::when( ($sort AND $direction), function ($query) use ($sort, $direction){

            $query->orderBy($sort, $direction);

        })->paginate(3);
    }

    public function update($data, string $id)
    {
        $task = Task::findOrFail($id);
		$task->text = $data['text'];
        $task->confirmed = $data['confirmed'];

        return $task->save();     
    }    

}
