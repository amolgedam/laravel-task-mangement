<div class="task-container drag-drop" id="project_{{ $project_id }}_{{ $priority }}" ondrop="drop(event, {{ $project_id }}, {{ $priority }})" ondragover="allowDrop(event)">
    @foreach ($tasks as $task)
        <div class="mt-2 drag-drop-record" draggable="true" ondragstart="drag(event)" id="task_{{ $task->id }}" data-task-id="{{ $task->id }}">
            <div class="card">
                <div class="card-header">
                    Task Name: {{ $task->name }}
                </div>
                <div class="card-body">
                    <div class="form-group mt-2">
                        <label for="task">Task Priority: </label>
                        @if ($task->priority==1)
                            <span class="badge bg-danger">Top</span>
                        @elseif($task->priority==2)
                            <span class="badge bg-warning">Next Down</span>
                        @elseif($task->priority==3)
                            <span class="badge bg-success">So On</span>
                        @endif
                    </div>
                    <div class="form-group mt-2">
                        <label for="task">Task Start: </label>
                        {{ \Carbon\Carbon::parse($task->start)->format('Y-m-d h:i A') }}
                    </div>
                    <div class="form-group mt-2">
                        <label for="task">Task End: </label>
                        {{ \Carbon\Carbon::parse($task->end)->format('Y-m-d h:i A') }}
                    </div>
                </div>
                <div class="card-footer float-end">
                    <a href="javascript:void(0)" data-task="{{ encrypt($task->id) }}" data-name="{{ ($task->name) }}" data-priority="{{ ($task->priority) }}" data-start="{{ \Carbon\Carbon::parse($task->start)->format('Y-m-d H:i') }}" data-end="{{ \Carbon\Carbon::parse($task->end)->format('Y-m-d H:i') }}" class="btn btn-sm btn-primary taskEdit" ><i class="fa fa-edit"></i> Edit</a>

                    <a href="javascript:void(0)" data-task="{{ encrypt($task->id) }}" class="btn btn-sm btn-danger taskDelete"><i class="fa fa-trash"></i> Delete</a>
                </div>
            </div>
        </div>
    @endforeach
</div>