@extends('layouts.app')

@section('content')
<style>
    /* .container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    } */

    .drag-drop{
        /* border: 3px solid #666; */
        /* background-color: #ddd; */
        border-radius: .5em;
        padding: 10px;
        cursor: move;
    }
    .drag-drop .over {
        border: 3px dotted #666;
    }
</style>
<div class="container">
    <div class="justify-content-center">
        <div class="card">
            <div class="card-header">
                {{ __('Projects-') }} {{ $project->name ?? NULL }}
                
                <a href="javascript:void(0)" id="createTask" class="btn btn-sm btn-primary float-end m-1" >{{ __("Create Task") }}</a>
                
                <a href="{{ URL::to('home') }}" class="btn btn-sm btn-danger float-end m-1" >{{ __("Back to Project") }}</a>
            </div>
        
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
        
                <div class="alert" id="custom-alert" style="display: none" role="alert"></div>
        
                <div class="row">
                    <div class="col-md-4">
                        <h1>Top</h1>
                        <div id="task-list_1"></div>
                    </div>
                    <div class="col-md-4" style="border-left: 2px solid #000">
                        <h1>Next</h1>
                        <div id="task-list_2"></div>
                    </div>
                    <div class="col-md-4" style="border-left: 2px solid #000">
                        <h1>So On</h1>
                        <div id="task-list_3"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>


<!-- Modal -->
<form method="POST" id="taskForm" action="{{ route('storeTask') }}" autoComplete="off">
@csrf
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Task</h5>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div class="alert" id="custom-alert-modal" style="display: none" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="task">Task Name <span class="error">*</span></label>
                    <input type="text" name="name" id="name" placeholder="Enter task name" class="form-control" />

                    <span id="err_task" style="display: none; color: red"></span>
                </div>

                <div class="form-group mt-2">
                    <label for="task">Task Priority<span class="error">*</span></label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="1">Top</option>
                        <option value="2">Next Down</option>
                        <option value="3">So on.</option>
                    </select>

                    <span id="err_priority" style="display: none; color: red"></span>
                </div>

                <div class="form-group mt-2">
                    <label for="task">Task Start<span class="error">*</span></label>
                    <input type="datetime-local" name="task_start" id="task_start" class="form-control" />

                    <span id="err_task_start" style="display: none; color: red"></span>
                </div>

                <div class="form-group mt-2">
                    <label for="task">Task End<span class="error">*</span></label>
                    <input type="datetime-local" name="task_end" id="task_end" class="form-control" />

                    <span id="err_task_end" style="display: none; color: red"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="taskModalClose">Close</button>
                <button type="submit" class="btn btn-primary" id="taskModalSave">Save</button>
            </div>
        </div>
        </div>
    </div>
</form>

@endsection

@section('end_js')
    @include('tasks/js')
@endsection