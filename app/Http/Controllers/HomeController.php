<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth, Carbon\Carbon;

use App\Models\Project;
use App\Models\ProjectTask;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())->get();
        return view('home', compact('projects'));
    }

    public function storeProject(Request $request){
        $project_id = NULL;
        if($request->project_id){
            $project_id = decrypt($request->project_id);
            $project = Project::find($project_id);
        }
        else{
            $project = new Project;
            $project->user_id = Auth::id();
        }
        $project->name = $request->name;

        $status = false;
        if($project_id==NULL){
            $message = "Unable to create project";
        }
        else{
            $message = "Unable to update project";
        }

        if($project->save()){
            $status = true;
            if($project_id==NULL){
                $message = "Project created successfully";
            }
            else{
                $message = "Project updated successfully";
            }
        }
        return response()->json(["code"=>200, "status" => $status, "message" => $message]);
    }

    public function projectDelete(Request $request, $id){
        $status = false;
        $message = "Unable to delete Project";

        if($id){
            $id = decrypt($id);
            if(Project::destroy($id)){
                $status = true;
                $message = "Product deleted successfully";
            }
        }
        return response()->json(["code"=>200, "status" => $status, "message" => $message]);
    }

    public function projectTasks(Request $request, $id){
        $id = decrypt($id);
        
        $project = Project::find($id);
        $tasks = [];
        return view('tasks/index', compact('project', 'tasks'));
    }

    function projectTaskList(Request $request){
        $id = decrypt($request->id);
        $tasks = ProjectTask::where('project_id', $id)->where('priority', $request->priority)->orderBy('priority', 'ASC')->get();
        
        $project_id = $id;
        $priority = $request->priority;
        $returnHTML = view('tasks/list', compact('project_id', 'priority', 'tasks'))->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function storeTask(Request $request){
        $task_id = NULL;
        if($request->task_id){
            $task_id = decrypt($request->task_id);
            $projectTask = ProjectTask::find($task_id);
        }
        else{
            $projectTask = new ProjectTask;
            $projectTask->user_id = Auth::id();
            $projectTask->project_id = decrypt($request->project_id);
        }
        $projectTask->name = $request->name;
        $projectTask->priority = $request->priority;
        $projectTask->start = Carbon::parse($request->task_start)->format('Y-m-d H:i:s');
        $projectTask->end = Carbon::parse($request->task_end)->format('Y-m-d H:i:s');

        $status = false;
        if($task_id==NULL){
            $message = "Unable to create task";
        }
        else{
            $message = "Unable to update task";
        }

        // dd($request->all(), $projectTask);
        if($projectTask->save()){
            $status = true;
            if($task_id==NULL){
                $message = "Task created successfully";
            }
            else{
                $message = "Task updated successfully";
            }
        }
        return response()->json(["code"=>200, "status" => $status, "message" => $message]);
    }

    public function taskUpdateDragDrop(Request $request){
        $projectTask = ProjectTask::find($request->taskId);
        $projectTask->priority = $request->priority;

        $status = false;
        $message = "Unable to update task";
        // dd($projectTask);
        if($projectTask->save()){
            $status = true;
            $message = "Task updated successfully";
        }
        return response()->json(["code"=>200, "status" => $status, "message" => $message]);
    }

    public function taskDelete(Request $request, $id){
        $status = false;
        $message = "Unable to delete Task";

        if($id){
            $id = decrypt($id);
            if(ProjectTask::destroy($id)){
                $status = true;
                $message = "Task deleted successfully";
            }
        }
        return response()->json(["code"=>200, "status" => $status, "message" => $message]);
    }

}
